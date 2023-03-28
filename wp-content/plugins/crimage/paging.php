<?PHP
    
	function check_integer($which) {
	
        if(isset($_REQUEST[$which])){
            if (intval($_REQUEST[$which])>0) {
				return intval($_REQUEST[$which]);
            } else {
                return false;
            }
        }
        return false;
    }//end of check_integer()

    function get_current_page() {
        
		if(($var=check_integer('wppage'))) {        
            return $var;
        } else {            
            return 1;
        }
    }//end of method get_current_page()

    function doPages($page_size, $thepage, $query_string, $total=0) {
        
        //per page count
        $index_limit = 6;

        //set the query string to blank, then later attach it with $query_string
        $query='';        
        if(strlen($query_string)>0){ $query = "&amp;".$query_string;}
        
        //get the current page number example: 3, 4 etc: see above method description
		$current = get_current_page();        
        $total_pages=ceil($total/$page_size);
        $start=max($current-intval($index_limit/2), 1);
        $end=$start+$index_limit-1;

        $html	= '<div class="paging">';

        if($current==1) {
            
			$html	.= '<span class="prn">&lt; Previous</span>&nbsp;';
			
        } else {
            
			$i = $current-1;
            $html	.= '<a href="'.$thepage.'?wppage='.$i.$query.'" class="prn" rel="nofollow" title="go to wppage '.$i.'">&lt; Previous</a>&nbsp;';
            $html	.= '<span class="prn">...</span>&nbsp;';
        }

        if($start > 1) {
		
            $i = 1;
			$html	.= '<a href="'.$thepage.'?wppage='.$i.$query.'" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
        }

        for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
		
            if($i==$current) { $html	.= '<span>'.$i.'</span>&nbsp;';  } else {
                $html	.= '<a href="'.$thepage.'?wppage='.$i.$query.'" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
            }
        }

        if($total_pages > $end){
		
            $i = $total_pages;
            $html	.= '<a href="'.$thepage.'?wppage='.$i.$query.'" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
        }

        if($current < $total_pages) {
            
			$i = $current+1;
			$html	.= '<span class="prn">...</span>&nbsp;';
            $html	.= '<a href="'.$thepage.'?wppage='.$i.$query.'" class="prn" rel="nofollow" title="go to page '.$i.'">Next &gt;</a>&nbsp;';
        
		} else { $html	.= '<span class="prn">Next &gt;</span>&nbsp;';  }      
       
        if ($total != 0) {  $html	.= '<p id="total_count">(total '.$total.' results)</p></div>'; }
        
		return $html;
    }
?>