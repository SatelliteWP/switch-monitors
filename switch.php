#!/opt/homebrew/bin/php
<?php
/**************************************************************************************************
 * 
 * Switch Monitors (mac)
 * 
 * The script takes for granted there are 2 monitors connected to the mac. Then, it switches the
 * position of the monitors.
 * 
 * SatelliteWP
 * 2024.09
 * 
 * Tested with:
 *  PHP 8.3.12
 *  Displayplacer 1.4.0
 *  macOS 14.5 (Sonoma)
 * 
 *************************************************************************************************/

class SwitchDisplay {

    /**
     * Start the script
     */
    public function start() {
        $displays = $this->getDisplays();

        if (count($displays) == 2) {
            $this->switch($displays);
        }
    }

    /**
     * Switch the position of the monitors by sending the command to displayplacer
    
     * @param array $displays Monitors
     * 
     * @return array
     */
    public function switch($displays) {
        $display1 = $displays[0];
        $display2 = $displays[1];

        $command = "/opt/homebrew/bin/displayplacer " . $this->getDisplayPlacerCommand($display2, $display1) . " " . $this->getDisplayPlacerCommand($display1, $display2) . "\n";

        $output = null;
        $result_code = null;

        exec($command, $output, $result_code);

        return [
            'output' => $output,
            'result_code' => $result_code
        ];
    }

    /**
     * Get the displayplacer command to place a monitor
     * 
     * @param array $display Monitor
     * @param array $otherDisplay Other monitor
     * 
     * @return string
     */
    public function getDisplayPlacerCommand($display, $otherDisplay) {
        $cmd = '"id:%s res:%s hz:%s color_depth:%s enabled:%s scaling:%s origin:%s degree:%s"';

        $result = sprintf($cmd, $display['Persistent screen id'], $display['Resolution'], $display['Hertz'], $display['Color Depth'], $display['Enabled'], $display['Scaling'], $otherDisplay['Origin'], $display['Rotation']);
        
        return $result;
    }

    /**
     * Get the list of monitors
     * 
     * @return array
     */
    public function getDisplays() {
        $output = [];
        exec('/opt/homebrew/bin/displayplacer list', $output);

        $monitors = [];
        $monitor = [];
        $key = null;
        $value = null;
        $previousKey = null;
        foreach($output as $line) {
            // Empty line means a new monitor
            if ($line == '') {
                $monitors[] = $monitor;
                $monitor = [];
            }
            // When this text is found, we are at the end of the list
            elseif (strpos($line, 'Execute the command') === 0) {
                break;
            }
            else {
                $parts = explode(': ', $line);

                // If the line contains a colon, it's a key-value pair
                if (strpos($line, ':' ) !== false) {
                    $key = $parts[0];

                    if (count($parts) == 2) {    
                        $value = $parts[1];
                        $value_parts = explode(' - ', $value);
                        if (count($value_parts) > 1) {
                            $value = $value_parts[0];
                        }

                        // If the line starts with two spaces, it's a subkey
                        if (substr($line, 0, 2) == '  ') {
                            $subkey = trim($key, " ");
                            $monitor[$previousKey][$subkey] = $value;
                        }
                        // Otherwise, normal key-value pair
                        else {
                            $monitor[$key] = $value;
                            $previousKey = null;
                        }
                    }
                    // If there is only one colon, it's a key to an array
                    else {
                        $previousKey = trim($key, " :");
                        $monitor[$previousKey] = [];
                    }
                }
            }
        }

        return $monitors;
    }
}

$sd = new SwitchDisplay();
$sd->start();
