<?php
namespace Fgsl;

use Laminas\Db\Adapter\AdapterServiceFactory;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Db\Adapter\Adapter;

class CsvExtractor {
    private array $config;
    private CsvExtractorInterface $decorator;


    public function __constructor(array $config, CsvExtractorInterface $decorator){
        $this->config = $config;
        $this->decorator = $decorator;
    }

    public function extractData(){
        $container = new ServiceManager();
        $container->setService('config', $this->config);
        
        $adapterServiceFactory = new AdapterServiceFactory();
        
        $adapter = $adapterServiceFactory($container,__FILE__);
        
        $handle = fopen($this->config['csvfile'],'r');
        
        $success = true;

        $counter = 0;
        while(!feof($handle)){
            $counter++;
            $row = fgetcsv($handle,0,',');
            
            if ($counter == 1 || empty($row[0])){
                continue;
            }    
            
            $sql = $this->decorator->getSqlStatement($row);
            
            try {
                $adapter->query(
                    $sql,
                    Adapter::QUERY_MODE_EXECUTE
                );
            } catch (\Exception $e) {    
                error_log($e->getMessage());
                $success = false;    
            }
        }
        fclose($handle);
        
        error_log("Extracting finished");
    }
}