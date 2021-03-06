<?php
/**
 * Exception class in /BazeZF/
 *
 * @category   BazeZF
 * @package    BazeZF_Core
 * @copyright  Copyright (c) 2008 BazeZF
 * @author     Harold Thetiot (hthetiot)
 */

class BaseZF_Exception extends Exception
{
    public $file;

    public $line;

    public $context;

    public function __construct($message = null, $code = 0, $file = null, $line = null, $context = array())
    {

        parent::__construct($message, $code);


        $this->file = $file;
        $this->line = $line;
        $this->context = $context;
    }

    /**
     * Gets the interesting lines in the interesting file
     */
    public function getSource($nbLine = 6)
    {
        if (!is_file($this->file)) {
            return;
        }

        $file = fopen($this->file, 'r');
        $beginLine = max(0, $this->line - $nbLine / 2);
        $endLine = $beginLine + $nbLine - 1;
        $code = '';
        $curLine = 0;

        while($line = fgets($file)) {

            $curLine++;

            if ($this->line == $curLine) {
                $lineLabel = 'ERR:';
            } else {
                $lineLabel = str_pad($curLine, 3, '0', STR_PAD_LEFT) . ':';
            }

            if ($curLine >= $beginLine && $curLine <= $endLine) {
                $code .= $lineLabel . $line;
            }

            if ($curLine > $endLine) {
                break;
            }
        }

        if (!preg_match('/^\<\?php/', ltrim($code))) {
            $code = "<?php\n" . $code;
        }

        return $code;
    }

    public function getContext()
    {
        return $this->context;
    }
}

