#!/bin/sh
# Simulate Traefik: Apache is on :3000, but the Host header has no port.
set -eu

base="${BASE_URL:-http://127.0.0.1:3000}"
host="${PROXY_HOST:-noahosterhout.com}"
fail=0

check() {
    path="$1"
    expect_status="$2"
    expect_location="$3"
    headers=$(curl -sI -H "Host: ${host}" --max-redirs 0 "${base}${path}")
    status=$(printf '%s\n' "$headers" | awk 'BEGIN{c=""} /^HTTP/{c=$2} END{print c}')
    location=$(printf '%s\n' "$headers" | awk 'BEGIN{IGNORECASE=1} /^Location:/{sub(/\r/,""); print substr($0, 11); exit}')

    ok=1
    if [ "$status" != "$expect_status" ]; then
        ok=0
    fi
    if [ "$expect_location" != "-" ] && [ "$location" != "$expect_location" ]; then
        ok=0
    fi
    if printf '%s' "$location" | grep -q ':3000'; then
        ok=0
    fi
    if printf '%s' "$location" | grep -q 'http://'; then
        ok=0
    fi

    if [ "$ok" -eq 1 ]; then
        printf 'OK  %s  %s  %s\n' "$status" "$location" "$path"
    else
        printf 'BAD %s  [%s] expected %s [%s]  %s\n' "$status" "$location" "$expect_status" "$expect_location" "$path"
        fail=1
    fi
}

echo "Host: ${host}"
check /blogs/haversine-formula.php 301 "https://${host}/"
check /blogs/ 301 "https://${host}/"
check '/load-blog.php?blog=haversine-formula' 301 "https://${host}/"
check /index.php 301 "https://${host}/"
check '/load-project.php?project=asop-app' 301 "https://${host}/projects/asop-app"
check /load-project.php 301 "https://${host}/"
check /projects/asop-app.php 301 "https://${host}/projects/asop-app"
check /projects/asop-app/ 301 "https://${host}/projects/asop-app"
check '/project.php?slug=asop-app' 301 "https://${host}/projects/asop-app"
check /project.php 301 "https://${host}/"
check /projects/InputFinder 301 "https://${host}/projects/inputfinder"
check /favicons 301 "https://${host}/favicons/"
check /css 301 "https://${host}/css/"

headers=$(curl -sI -H "Host: ${host}" "${base}/")
for name in Content-Security-Policy X-Content-Type-Options Referrer-Policy Strict-Transport-Security X-Frame-Options; do
    if ! printf '%s\n' "$headers" | grep -qi "^${name}:"; then
        echo "BAD missing ${name} on /"
        fail=1
    fi
done
if printf '%s\n' "$headers" | grep -qi '^X-Powered-By:'; then
    echo "BAD X-Powered-By is still set"
    fail=1
fi
if printf '%s\n' "$headers" | grep -qi 'unsafe-inline'; then
    echo "BAD CSP allows unsafe-inline"
    fail=1
fi
server=$(printf '%s\n' "$headers" | awk 'BEGIN{IGNORECASE=1} /^Server:/{sub(/\r/,""); print substr($0, 9); exit}')
if [ "$server" != "Apache" ]; then
    echo "BAD Server token is [${server}]"
    fail=1
fi

if [ "$fail" -ne 0 ]; then
    exit 1
fi
echo "proxy redirect checks passed"
