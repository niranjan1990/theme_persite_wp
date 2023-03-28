<?php
require_once("sphinxapi.php");


/**
 * Description of SphinxSearch
 *  Common code to search indexes on CR's sphinx server
 * @author Michael Gorham
 */
class SphinxSearch {

// put these in a config file?
    var $_wp_base_url = "reviews.golfreview.com";
    
    var $_wp_server = "172.20.10.213";
    var $_wp_username = "";
    var $_wp_password = "";
    var $_wp_database = "golf_wp";
    var $_wp_index = "golf_wp";
    var $_wp_table_prefix = "";

    var $_forums_base_url = "forums.golfreview.com";
    var $_forums_server = "vbdb01.sc4.e-centives.com";
    var $_forums_username = "root";
    var $_forums_password = "";
    var $_forums_database = "vb3";
    var $_forums_index = "golf_forums";
    var $_forums_table_prefix = "golf_";

    var $_venice_base_url = "www.golfreview.com";
    var $_venice_server = "crdb01.crsc4.e-centives.com";
    var $_venice_username = "cruser";
    var $_venice_password = "@Rukest2";
    var $_venice_database = "venice";
    var $_venice_index = "golf_venice";
    
    var $_sql = "";
	var $_index = "";
    
	var $_error = "";
    
	function GetForumPosts ($query, $date_sort = false, $result_count = 10)
	{
		assert ( is_string( $this->_forums_server ));
		assert ( is_string( $this->_forums_username ));
		assert ( is_string( $this->_forums_password ));
		assert ( is_string( $this->_forums_database ));
		assert ( is_string( $this->_forums_index ));

        $this->_index = $this->_forums_index;
        $docids = $this->_GetSphinx($query, true, $result_count);

        if (!$docids)
            return false;
        
        $_sql = "SELECT t.threadid, t.title, p.pagetext, t.postusername, t.postuserid, DATE_Format(from_unixtime(t.dateline),\"%a %b %D, %Y %l:%i %p\") as thisdate FROM " . 
                $this->_forums_table_prefix . "thread t 
                JOIN " . $this->_forums_table_prefix . "post p ON t.firstpostid = p.postid
                WHERE t.threadid In(" . $docids . ")";        

        mysql_connect($this->_forums_server, $this->_forums_username, $this->_forums_password) or die(mysql_error());
        mysql_select_db($this->_forums_database) or die(mysql_error());

        $result = mysql_query($_sql) or die(mysql_error());

        $sphinx_result = array();
        
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $sr = new SphixResult;

                $sr->id = $row["threadid"];
                $sr->title = $row["title"];
                $sr->userid = $row["postuserid"];
                $sr->date = $row["thisdate"];

                $sr->userurl = "http://" . $this->_forums_base_url . "/member.php?u=" . $sr->userid;

                $sr->text = strip_tags($row["pagetext"]);

                if (strlen($sr->text) > 180)
                    $sr->text = substr($sr->text, 0, 180);


                $sr->url = "http://" . $this->_forums_base_url . "/showthread.php?t=" . $sr->id;

                array_push($sphinx_result, $sr);
            }

            return $sphinx_result;
        }
        else {
            return false;
        }
	}

	function GetCRProducts($query, $date_sort = false, $result_count = 10)
	{
		assert ( is_string( $this->_venice_server ));
		assert ( is_string( $this->_venice_username ));
		assert ( is_string( $this->_venice_password ));
		assert ( is_string( $this->_venice_database ));
		assert ( is_string( $this->_venice_index ));

        
        $this->_index = $this->_venice_index;
        $docids = $this->_GetSphinx($query, $date_sort, $result_count);

        if (!$docids)
            return false;

        $conn = mssql_connect($this->_venice_server, $this->_venice_username, $this->_venice_password);
        if( $conn === false ) {
             die( print_r( sqlsrv_errors(), true));
        }
        
        mssql_select_db('Venice', $conn);

        $sphinx_result = array();
        
        $sql = "SELECT productid, categoryid, manufacturer_name, product_name, product_description ";
        $sql .= " FROM products p ";
        $sql .= " INNER JOIN manufacturers m ON p.manufacturerid = m.manufacturerid ";
        $sql .= " WHERE productid in(" . $docids . ")";
        
        $stmt = mssql_query( $sql, $conn );

        if( $stmt === false ) {
             die( print_r( mssql_get_last_message(), true));
        }
        
        // TODO:  because SQL sorts by productid asc, there is no way to pass in and sort by ranking
        // get results from sql, then merge them into the sorted array for appropriate ranking
//        $sorted_result = explode(",", $docids);
        
        if ($stmt) {
            if (mssql_num_rows( $stmt )) {
                while( $row = mssql_fetch_assoc( $stmt ) ) {
                    $sr = new SphixResult;

                    $sr->id = $row["productid"];
                    $sr->title = $row["manufacturer_name"] . " " . $row["product_name"];
                    $sr->text = $row["product_description"];

                    $sr->url = "http://" . $this->_venice_base_url . "/PRD_" . $row["productid"] . "_" . $row["categoryid"] . "crx.aspx";

                    array_push($sphinx_result, $sr);
                }
                mssql_free_result( $stmt );
                
                return $sphinx_result;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
	}

	function GetWPPosts ($query, $date_sort = false, $result_count = 10)
	{
		assert ( is_string( $this->_wp_server ));
		assert ( is_string( $this->_wp_username ));
		assert ( is_string( $this->_wp_password ));
		assert ( is_string( $this->_wp_database ));
		assert ( is_string( $this->_wp_index ));

        $this->_index = $this->_wp_index;
        $docids = $this->_GetSphinx($query, true, $result_count);

        if (!$docids)
            return false;
        
        $_sql  = "SELECT ID, post_title, post_content, post_date FROM wp_posts WHERE post_status = 'publish' AND post_type = 'post' AND ID In(" . $docids . ")";
        
        mysql_connect($this->_wp_server, $this->_wp_username, $this->_wp_password) or die(mysql_error());
        mysql_select_db($this->_wp_database) or die(mysql_error());

        $result = mysql_query($_sql) or die(mysql_error());

        $sphinx_result = array();
        
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $sr = new SphixResult;

                $sr->id = $row["ID"];
                $sr->title = $row["post_title"];
                $sr->date = $row["post_date"];


                $sr->text = strip_tags($row["post_content"]);

                if (strlen($sr->text) > 180)
                    $sr->text = substr($sr->text, 0, 180);


                $sr->url = "http://" . $this->_wp_base_url . "/?p=" . $sr->id;

                array_push($sphinx_result, $sr);
            }

            return $sphinx_result;
        }
        else {
            return false;
        }
	}
    
	function SetDatabase ( $database )
    {
        $this->_database = $database;
    }
    
	function SetServer ( $server )
    {
        $this->_server = $server;
    }

	function SetUsername ( $username )
    {
        $this->_username = $username;
    }
    
	function SetPassword ( $password )
    {
        $this->_password = $password;
    }
    
	function SetSearchIndex ( $index )
    {
        $this->_index = $index;
    }

    
    function _GetSphinx($query, $date_sort = false, $result_count = 10)
    {
		assert ( is_string($query) );
        assert ( strlen($query) > 0 );
		assert ( is_string($this->_index));
        assert ( strlen($this->_index) > 0 );
        assert ( is_int($result_count) );

        
        $s = new SphinxClient;
//        $s->setServer("wp-search.ops.invenda.com", 9312);
        $s->setServer("mdb01.ops.invenda.com", 9312);
        
//        $s->setMatchMode(SPH_MATCH_PHRASE);
//        $s->SetFieldWeights(array("post_title" => 70, "post_content" => 30));
        $s->setMaxQueryTime(30);
        $s->SetLimits(0, $result_count);
        $s->SetMatchMode( SPH_MATCH_EXTENDED2 );
        $s->SetRankingMode( SPH_RANK_PROXIMITY_BM25 );

        // switch from phrase result accuracy to more recent posts by sorting by the post date
        if ($date_sort) {
           $s->SetSortMode( SPH_SORT_ATTR_DESC, "date1" );
        }

        $result = $s->query($query, $this->_index);
        
        if ($result) {
            if ($result["total_found"] > 0) {
                $ids = array();
                foreach ($result["matches"] as $key => $value) {
                    array_push($ids, $key);
                }
                return implode(",", $ids);
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }
}

class SphixResult {
    
	var $id = "";
    var $title = "";
    var $text = "";
    var $url = "";
    var $thumbnail = "";
    var $filepath = "";
    var $username = "";
    var $userurl = "";
    var $userid = "";
    var $date = "";    
}

?>