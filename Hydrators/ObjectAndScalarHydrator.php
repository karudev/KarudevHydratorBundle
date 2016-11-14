<?php
namespace KarudevHydratorBundle\Hydrators;

use Doctrine\ORM\Internal\Hydration\ObjectHydrator;

/**
 * Mixed scalars data into the parent Object
 * @author Dolyveen Renault, Karudev Informatique <renault@karudev.fr>
 */
class ObjectAndScalarHydrator extends ObjectHydrator{
    

    protected function hydrateAllData()
    {
        $result = parent::hydrateAllData();
        
        # Entity parent
        $parentEntity = current($this->_rsm->getAliasMap());

        $arrayOfObject = [];
        $index = 0;
        foreach ($result as $key => $row1){

            if($row1[0] instanceof $parentEntity){
               $arrayOfObject[$index] = $row1[0];
               $index++;
            }else{
                foreach ($row1 as $key2 => $row2) {
                    if(is_string($key2)){
                       $method = 'set'.ucfirst($key2);
                       $arrayOfObject[$index-1]->$method($row2); 
                    }elseif($row2 != null){
                       $class = get_class($row2);
                       $class = explode("\\",$class);
                       $method = 'set'.ucfirst($class[3]);
                       if(!method_exists($class,$method)){
                           $method = 'add'.ucfirst($class[3]);
                       }
                       $arrayOfObject[$index-1]->$method($row2); 
                    }
                }
            }
            
        }
        return $arrayOfObject; 
    }

  
    
}
