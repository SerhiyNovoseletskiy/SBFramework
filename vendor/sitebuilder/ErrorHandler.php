<?php

namespace app\sitebuilder;


class ErrorHandler {
    private $exception;

    /**
     * @var integer maximum number of source code lines to be displayed. Defaults to 19.
     */
    public $maxSourceLines = 19;
    /**
     * @var integer maximum number of trace source code lines to be displayed. Defaults to 13.
     */
    public $maxTraceSourceLines = 13;

    public function register() {
        ini_set('display_errors', SB_DEBUG);
        set_exception_handler([$this, 'exceptionHandle']);
        set_error_handler([$this, 'errorHandle']);
    }

    public function exceptionHandle($exception) {
        $this->exception = $exception;
        $this->render($exception);
    }

    public function errorHandle($error) {

    }

    private function render($exception) {

        try {
            echo new LayoutRender('errors/'.$exception->statusCode, [
                'exception' => $exception,
                'handler' => $this
            ]);
        } catch (\Exception $e) {
            echo new LayoutRender('errors/error', [
                'exception' => $exception,
                'handler' => $this
            ]);
        }
    }

    public function htmlEncode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }


    /**
     * Converts arguments array to its string representation
     *
     * @param array $args arguments array to be converted
     * @return string string representation of the arguments array
     */
    public function argumentsToString($args)
    {
        $count = 0;
        $isAssoc = $args !== array_values($args);

        foreach ($args as $key => $value) {
            $count++;
            if ($count>=5) {
                if ($count>5) {
                    unset($args[$key]);
                } else {
                    $args[$key] = '...';
                }
                continue;
            }

            if (is_object($value)) {
                $args[$key] = '<span class="title">' . $this->htmlEncode(get_class($value)) . '</span>';
            } elseif (is_bool($value)) {
                $args[$key] = '<span class="keyword">' . ($value ? 'true' : 'false') . '</span>';
            } elseif (is_string($value)) {
                $fullValue = $this->htmlEncode($value);
                if (mb_strlen($value, 'utf8') > 32) {
                    $displayValue = $this->htmlEncode(mb_substr($value, 0, 32, 'utf8')) . '...';
                    $args[$key] = "<span class=\"string\" title=\"$fullValue\">'$displayValue'</span>";
                } else {
                    $args[$key] = "<span class=\"string\">'$fullValue'</span>";
                }
            } elseif (is_array($value)) {
                $args[$key] = '[' . $this->argumentsToString($value) . ']';
            } elseif ($value === null) {
                $args[$key] = '<span class="keyword">null</span>';
            } elseif (is_resource($value)) {
                $args[$key] = '<span class="keyword">resource</span>';
            } else {
                $args[$key] = '<span class="number">' . $value . '</span>';
            }

            if (is_string($key)) {
                $args[$key] = '<span class="string">\'' . $this->htmlEncode($key) . "'</span> => $args[$key]";
            } elseif ($isAssoc) {
                $args[$key] = "<span class=\"number\">$key</span> => $args[$key]";
            }
        }
        $out = implode(", ", $args);

        return $out;
    }

    public function renderCallStackItem($file, $line, $class, $method, $args, $index)
    {

        $lines = [];
        $begin = $end = 0;
        if ($file !== null && $line !== null) {
            $line--; // adjust line number from one-based to zero-based
            $lines = @file($file);
            if ($line < 0 || $lines === false || ($lineCount = count($lines)) < $line + 1) {
                return '';
            }

            $half = (int) (($index == 1 ? $this->maxSourceLines : $this->maxTraceSourceLines) / 2);
            $begin = $line - $half > 0 ? $line - $half : 0;
            $end = $line + $half < $lineCount ? $line + $half : $lineCount - 1;
        }
        return $this->renderFile([
            'file' => $file,
            'line' => $line,
            'class' => $class,
            'method' => $method,
            'index' => $index,
            'lines' => $lines,
            'begin' => $begin,
            'end' => $end,
            'args' => $args,
        ]);
    }

    /**
     * Renders a view file as a PHP script.
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     */
    public function renderFile($_params_)
    {
            $_params_['handler'] = $this;
            ob_start();
            ob_implicit_flush(false);
            extract($_params_, EXTR_OVERWRITE);
            require __DIR__. '/../../views/errors/callStackItem.php';

            return ob_get_clean();

    }
}
