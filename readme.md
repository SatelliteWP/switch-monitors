# Switch Monitors for Mac

You have a docking station for your mac and 2 monitors. For some reason, your display configuration often switches your monitor order.

Not anymore!

## Requirements

First, you need to install [Homebrew](https://brew.sh/).

Once done, you need to install **DisplayPlacer**:

    brew install displayplacer

And **PHP**:

    brew install php

## Installation

To install this script, simply go to Library folder and clone this repository:

    cd /Library
    sudo git clone https://github.com/SatelliteWP/switch-monitors.git
    sudo chmod +x /Library/switch-monitors/run.sh

That's it! The installation is completed.

You can call the monitor switching by calling the script from the terminal:

    /Library/switch-monitors/run.sh

## Mapping to a key

It is faster to use the script by mapping it to a key on your keyboard, for instance, using F19.

To do so, open Automator.

Create a new "Quick Action" script. Pick "Run AppleScript" and paste the following code :

    on run {input, parameters}
	     do shell script "/Library/switch-monitors/run.sh"
	     return input
    end run

Make sure to select : Workflow receives **Automatic (Nothing)** in **any application**. Leave other options as is!

![image](https://github.com/user-attachments/assets/3679e8e1-f1bf-43eb-9a65-c7b5b6584533)

Save the script as "Switch Monitors".

Then go to Settings > Keyboard > Keyboard Shortcuts..

![image](https://github.com/user-attachments/assets/b5ebb349-189d-4965-8753-a4e19de6cf0b)


Then select Services > General and check the **Switch Monitors** and double click at the end of the line to map the right key.

![image](https://github.com/user-attachments/assets/e0597440-e7e9-4e57-a8e1-656b8ad7737b)

That's it! You can switch monitors by pressing the right key on your keyboard.
