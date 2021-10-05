<?php
use Laminas\Db\Adapter\AdapterServiceFactory;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Adapter\Adapter;

require 'vendor/autoload.php';
require 'functions.php';

$container = new ServiceManager();
$config = require 'config.php';
$container->setService('config', $config);

$adapterServiceFactory = new AdapterServiceFactory();

$adapter = $adapterServiceFactory($container,__FILE__);

$handle = fopen($config['csvfile'],'r');

$counter = 0;
while(!feof($handle)){
    $counter++;
    $row = fgetcsv($handle,0,',');
    
    if ($counter == 1 || empty($row[0])){
        continue;
    }    
    
    $sql = getSqlStatement($row);
    
    try {
        $adapter->query(
            $sql,
            Adapter::QUERY_MODE_EXECUTE
        );
    } catch (\Exception $e) {    
        echo $e->getMessage() . "\n";    
    }
}
fclose($handle);

echo "\nExtracting finished\n";

