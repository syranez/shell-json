<?php

/**
 * maps json data to a directory structure.
 *
 * Mappingrules:
 *
 *    - objects / arrays are mapped to directorys
 *    - primitive properties are mapped to files containing the value
 *
 */
final class BashJson {

    /**
     * contains the current directory while processing the json.
     *
     * @value array
     * @access private
     */
    private $dirStack = array();

    /**
     * name of the directory in which the json data will be made available.
     *
     * @value string
     * @access private
     */
    private $directory = '';

    /**
     * name of the root node.
     *
     * @value string
     * @access private
     */
    private $rootNode = '';

    /**
     * __construct
     *
     * @param string $directory absolute path in which the json data will be made available
     * @param string $rootNode the name of the root director of the json data in $directory
     * @access public
     * @final
     */
    public function __construct($directory = null, $rootNode = null) {

        if (isset($directory)) {
            $this->setDirectory($directory);
        }
        if (isset($rootNode)) {
            $this->setRootNode($rootNode);
        }
    }

    /**
     * maps the json data to a directory structure.
     *
     * Returns
     *
     *     1, if argv is not available
     *     2. if data is not correct json
     *     3. if a directory could not created.
     *     4. if a file could not created.
     *
     * @param number $argc number of command line arguments
     * @param array $argv command line arguments
     * @return number
     * @access public
     */
    public function create ($argc, $argv) {

        if (!is_array($argv)) {
            return 1;
        }

        $json = json_decode($argv[$argc-1], true);

        if (is_null($json)) {
            return 2;
        }

        $returnValue = $this->process($this->getRootNode(), $json);

        return $returnValue;
    }

    /**
     * processes the json, creates directorys and files.
     *
     * @param string $key current directory node
     * @param array $json data of the current node
     * @return number
     * @acces private
     */
    private function process ($key, $json) {

        $this->pushStack($key);
        if (!$this->createDirectory()) {
            return 3;
        }

        foreach ($json as $index => $value) {
            if (is_array($value)) {
                $returnValue = $this->process($index, $value);
                if ($returnValue !== 0) {
                    return $returnValue;
                }
            } else {
                // simple value
                if (!$this->createFile($index, $value)) {
                    return 4;
                }
            }
        }

        $this->popStack();

        return 0;
    }

    /**
     * creates a file entry with primitve value.
     *
     * @param string $name name of file
     * @param string $content content of file
     * @access private
     * @return boolean
     */
    private function createFile($name, $content) {

        $file  = implode('/', $this->dirStack);
        $file .= '/' . $name;

        if (file_put_contents($this->getDirectory() . '/' . $file, $content) === false) {
            // the value can be a null value.
            return false;
        }

        return true;
    }

    /**
     * creates a directory entry.
     *
     * @access private
     * @return boolean
     */
    private function createDirectory () {

        $dir = implode('/', $this->dirStack);

        if (!mkdir($this->getDirectory() . '/' . $dir, 0775, true)) {
            return false;
        }

        return true;
    }

    /**
     * adds a directory entry from the directory stack.
     *
     * @param string $directory
     * @access private
     */
    private function pushStack($directory) {

        $this->dirStack[] = $directory;
    }

    /**
     * removes a directory entry from the directory stack.
     *
     * @access private
     */
    private function popStack() {

        array_pop($this->dirStack);
    }

    /**
     * sets the node directory of the json data.
     *
     * @param string $root
     * @acces private
     */
    private function setRootNode($rootNode) {

        $this->rootNode = $rootNode;
    }

    /**
     * gives the node directory of the json data.
     *
     * @return string
     * @acces private
     */
    private function getRootNode() {

        return $this->rootNode;
    }

    /**
     * sets the directory in which the json data will be made available
     *
     * @param string $directory
     * @acces private
     */
    private function setDirectory($directory) {

        $this->directory = $directory;
    }

    /**
     * gives the directory in which the json data will be made available
     *
     * @return string
     * @acces private
     */
    private function getDirectory() {

        return $this->directory;
    }
}

$options = getopt('', array(
    'directory:',
    'root::'
));

$directory = null;
if (isset($options['directory'])) {
    $directory = $options['directory'];
}

$rootNode = null;
if (isset($options['root'])) {
   $rootNode = $options['root'];
}

$bj = new BashJson($directory, $rootNode);
return $bj->create($argc, $argv);
