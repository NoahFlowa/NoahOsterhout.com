---
title: InputFinder
description: A Windows app that shows which keyboard or program is sending keystrokes.
---

# InputFinder

## Overview

InputFinder is a Windows app that shows which keyboard or program is sending keystrokes. It records every key in the background, names the device when Windows provides one, and marks input that was injected by software.

Choose one key to watch. Click **Watch key**, then press that key. The banner, the event list, and the device counts follow the key you picked. Number-row and numpad keys stay separate. The choice is saved in `%LocalAppData%\InputFinder\settings.json` and is not part of the repository.

## Build

Requires the [.NET 10 SDK](https://dotnet.microsoft.com/download) on Windows.

```
dotnet build
```

Run `bin/Debug/net10.0-windows/InputFinder.exe` after a debug build, or `bin/Release/net10.0-windows/InputFinder.exe` after `dotnet build -c Release`.

## Logs

While InputFinder is open it writes a CSV under a `logs` folder. That file contains keystrokes, device names, and the title of the foreground window. Keep those files private, and do not commit or publish them.

## Links

[InputFinder on GitHub](https://github.com/NoahFlowa/InputFinder)
