  // Przygotowanie surowej listy miast:
  $citiesRawArray = [];
  foreach($cities as $city)
  {
    $citiesRawArray[] = $city['name'];
  }

  // Funkcja generująca kombinacje bez powtórzeń:
  // Źródło: https://stackoverflow.com/a/8880362
  function getCombinations($base,$n){
    
    $baselen = count($base);
    if($baselen == 0){
        return;
    }
        if($n == 1){
            $return = array();
            foreach($base as $b){
                $return[] = array($b);
            }
            return $return;
        }else{
            //get one level lower combinations
            $oneLevelLower = getCombinations($base,$n-1);
    
            //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
            $newCombs = array();
    
            foreach($oneLevelLower as $oll){
    
                $lastEl = $oll[$n-2];
                $found = false;
                foreach($base as  $key => $b){
                    if($b == $lastEl){
                        $found = true;
                        continue;
                        //last element found
    
                    }
                    if($found == true){
                            //add to combinations with last element
                            if($key < $baselen){
    
                                $tmp = $oll;
                                $newCombination = array_slice($tmp,0);
                                $newCombination[]=$b;
                                $newCombs[] = array_slice($newCombination,0);
                            }
    
                    }
                }
    
            }
    
        }
        return $newCombs;
    }

  // Generowanie listy koniecznych linii do pobrania:
  $lines = getCombinations($citiesRawArray, 2);