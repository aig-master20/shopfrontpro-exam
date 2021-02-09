<?php

class DirectoryParser 
{
    /**
     * A static array with the list of paths
     *
     * @var array
     */
    private static $paths = [
        '/home/user/folder1/folder2/kdh4kdk8.txt',
        '/home/user/folder1/folder2/565shdhh.txt',
        '/home/user/folder1/folder2/folder3/nhskkuu4.txt',
        '/home/user/folder1/iiskjksd.txt',
        '/home/user/folder1/folder2/folder3/owjekksu.txt'
    ];

    /**
     *
     * I made this private so it cannot be instantiated
     *
     */
    private  function __construct() {

    }

    /**
     * Convert the array list of paths to tree array
     *
     * @return array
     */
    private static function convertToTreeArray() 
    {
        $tree = [];

        foreach (self::$paths as $path) {
            $pathArr = explode('/', $path);
            if (sizeof($pathArr) > 0) {
                $arrDir = &$tree;
                foreach ($pathArr as $key => $dir) {
                    if (!$dir)
                        continue;

                    if (($key+1) == sizeof($pathArr)) {
                        if(!$arrDir) $arrDir = [];
                        array_push($arrDir, $dir);
                    } else {    
                        if (empty($arrDir)) $arrDir[$dir] = []; 
                        $arrDir = &$arrDir[$dir];
                    }
                }
            }
        }
        
        return $tree; 
    }

    /**
     * Convert tree array to string
     *
     * @param array     $array The array to convert
     * @param integer   $maxDepth Maximum depth of the path
     * @param integer   $maxLeaves Maximum leaves/files of the path
     * @param integer   $level Level of directory which is default to 0
     * 
     * @return  string
     */
    private static function convertToString($array, $maxDepth, $maxLeaves, $level = 0) 
    {
        $string = '';
        $indent = 4;
        $leaves = 0 ;
        ksort($array, SORT_STRING);
        foreach ($array as $key => $a) {
            $space = $indent * $level;

            if(is_array($a)) {
                if($level == $maxDepth) break;
                self::concatSpacing($string, $space);
                $string .= $key."<br>";
            } else {
                if($maxLeaves > $leaves) { 
                    self::concatSpacing($string, $space);
                    $string .= $a."<br>";
                }
                $leaves++;
            }

            if (is_array($a)) {
                $level++;
                $string .= self::convertToString($a, $maxDepth, $maxLeaves, $level);
            }
        }
        return $string;
    } 

    /**
     * Concat spacing to string
     *
     * @param string    $string The string to concat the spacing
     * @param integer   $space Number of spaces
     * 
     * @return  string
     */
    private static function concatSpacing(&$string, $space) 
    {
        for ($i = 0; $i < $space; $i++) 
            $string .= "&nbsp";
    }

    /**
     * Print the path tree array
     *
     * @param integer   $string Maximum depth of the path
     * @param integer   $space Maximum leaves of the path
     * 
     * @return  string
     */
    public static function printPaths($maxDepth, $maxLeaves) 
    {
        $arr = self::convertToTreeArray(self::$paths);
        print(self::convertToString($arr, $maxDepth, $maxLeaves));
    }

    /**
     * Generate random paths
     *
     * @param string    $string The string to concat the spacing
     * @param integer   $space Number of spaces
     * @param integer   $maxDepth Maximum depth of path
     * @param integer   $maxFiles Maximum files of last branch
     * 
     * @return  array
     */
    public static function generateRandomPaths($base, $numPaths, $maxDepth, $maxFiles)
    {
        $arr = [];
        $paths = [];
        $foldersCount = [];
        for ($i = 0; $i < $numPaths; $i++) {
            array_push($paths, self::generatePath($maxDepth));
        }

        foreach($paths as $path) {
            $folders = $path['folders'];
            $pathStr = $base.implode($folders, '/');
            $lastKeyFolders = sizeof($folders) - 1;
            
            if (!isset($foldersCount[$folders[$lastKeyFolders]])) $foldersCount[$folders[$lastKeyFolders]] = 0;
            $lastFolderCount = $foldersCount[$folders[$lastKeyFolders]];
        
            if ($lastFolderCount < $maxFiles) $pathStr .= '/'.$path['file'];

            array_push($arr, $pathStr);
            
            $foldersCount[$folders[$lastKeyFolders]]++ ;
        }

        return $arr;
    }

    /**
     * Generate path array with folders and file
     *
     * @param integer   $maxDepth Maximum depth of branch
     * 
     * @return  array
     */
    private static function generatePath($maxDepth)
    {
        $path = [];
        $path['folders'] = self::generateRandomFolders($maxDepth);
        $path['file'] = self::generateRandomFile();
        return $path;
    }

    /**
     * Generate random folders
     *
     * @param integer   $maxDepth Maximum depth of branch
     * 
     * @return  array
     */
    private static function generateRandomFolders($maxDepth)
    {
        $folders = [];
        $folder = 'folder';
        $folderNum = rand(1, $maxDepth);
        $i = 1;
        while($i <= $folderNum) {
            array_push($folders, $folder.$i);
            $i++;
        }
        return $folders;
    }

    /**
     * Generate random file
     * 
     * @return  string
     */
    private static function generateRandomFile()
    {
        $length = 8;    
        $file = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 1, $length) . '.txt';
        return $file;
    }
}