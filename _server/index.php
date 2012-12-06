<?php

/**
 * Console interface
 */
interface ConsoleInterface
{
    /**
     * Write a line
     * @param string $line
     * @return ConsoleInterface
     */
    public function writeln($line);

    /**
     * Execute commands
     * @param string $command
     * @return ConsoleInterface
     */
    public function execute($command);

    /**
     * Quit console
     * @return void
     */
    public function quit();
}

class Console implements ConsoleInterface
{
    const EOL = "\r\n";

    public function __construct()
    {
        header('Content-type: text/plain; charset=utf-8');
        $this
            ->_commentln("```````````````````````````````````````````````````````````````")
            ->_commentln(" \"Goodbye Setup\" Shell Script")
            ->_commentln("```````````````````````````````````````````````````````````````")
            ->_commentln("  You can use this script via 'curl' command.")
            ->_commentln("  See help with following command:")
            ->_commentln("    $ curl setup.goodby.org | sh")
            ->_commentln("")
            ->_commentln("```````````````````````````````````````````````````````````````")
        ;
    }

    /**
     * {@inherit}
     */
    public function writeln($line)
    {
        echo 'echo ', escapeshellarg($line), self::EOL;
        return $this;
    }

    /**
     * {@inherit}
     */
    public function execute($command)
    {
        echo $command, self::EOL;
        return $this;
    }

    /**
     * {@inherit}
     */
    public function quit()
    {
        exit;
    }

    /**
     * @param string $line
     * @return Console
     */
    private function _commentln($line)
    {
        echo '# ', $line, self::EOL;
        return $this;
    }
}

class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function asLowerSnake()
    {
        $name = strtolower($this->name);
        $name = strtr($name, '-', '_');
        return $name;
    }

    /**
     * @return string
     */
    public function asUpperSnake()
    {
        return strtoupper($this->asLowerSnake());
    }

    /**
     * @return string
     */
    public function asCamel()
    {
        $name = strtolower($this->name);
        $name = strtr($name, '-', ' ');
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

    /**
     * @return string
     */
    public function asDashed()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if ( preg_match('/^[a-z][a-z0-9]*(-[a-z][a-z0-9]*)*$/', $this->name) ) {
            return true;
        }

        return false;
    }
}

function show_help(ConsoleInterface $console)
{
    $console
        ->writeln("usage:")
        ->writeln("    $ curl setup.goodby.org/<vendor-name>/<package-name> | sh")
        ->writeln("")
        ->writeln("    vendor-name  : Your github account name.")
        ->writeln("                   a-z, 0-9 and dash(-) is available.")
        ->writeln("    package-name : Your github repository name.")
        ->writeln("                   a-z, 0-9 and dash(-) is available.")
        ->writeln("")
        ->writeln("example:")
        ->writeln("    $ curl setup.goodby.org/alice-brown/csv-parser | sh")
    ;
}

$console = new Console();

if ( isset($_SERVER['REQUEST_URI']) === false ) {
    show_help($console);
    $console->quit();
}

$pathinfo = trim($_SERVER['REQUEST_URI'], '/');

if ( ! preg_match('#^(?P<vendor_name>[^/]+)/(?P<package_name>[^/]+)$#', $pathinfo, $matches) ) {
    $console->writeln("Invalid request.");
    show_help($console);
    $console->quit();
}

$vendorName  = new Name($matches['vendor_name']);
$packageName = new Name($matches['package_name']);

if ( $vendorName->isValid() === false ) {
    $console->writeln("Invalid vendor name: ".$vendorName->getName());
    show_help($console);
    $console->quit();
}

if ( $packageName->isValid() === false ) {
    $console->writeln("Invalid package name: ".$packageName->getName());
    show_help($console);
    $console->quit();
}

$substitutionMap = array(
    '__vendor-name__'  => $vendorName->asDashed(),
    '__vendor_name__'  => $vendorName->asLowerSnake(),
    '__VENDOR_NAME__'  => $vendorName->asUpperSnake(),
    '__VendorName__'   => $vendorName->asCamel(),
    '__package-name__' => $packageName->asDashed(),
    '__package_name__' => $packageName->asLowerSnake(),
    '__PACKAGE_NAME__' => $packageName->asUpperSnake(),
    '__PackageName__'  => $packageName->asCamel(),
);

$commands = file_get_contents(__DIR__.'/setup.sh');
$commands = str_replace(array_keys($substitutionMap), array_values($substitutionMap), $commands);

$console->execute($commands);
$console->quit();
