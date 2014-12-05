<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


/*
	V4.63 17 May 2005  (c) 2000-2005 John Lim (jlim@natsoft.com.my). All rights reserved.
	  Released under both BSD license and Lesser GPL library license. 
	  Whenever there is any discrepancy between the two licenses, 
	  the BSD license will take precedence. 
	  Set tabs to 4 for best viewing.

  	This class provides recordset pagination with 
	First/Prev/Next/Last links. 
	
	Feel free to modify this class for your own use as
	it is very basic. To learn how to use it, see the 
	example in adodb/tests/testpaging.php.
	
	"Pablo Costa" <pablo@cbsp.com.br> implemented Render_PageLinks().
	
	Please note, this class is entirely unsupported, 
	and no free support requests except for bug reports
	will be entertained by the author.


	copie de ADODB_Pager {
*/


class Pagination {
	var $id; 	// unique id for pager (defaults to 'adodb')
	var $db; 	// ADODB connection object
	var $sql; 	// sql used
	var $rs;	// recordset generated
	var $curr_page;	// current page number before Render() called, calculated in constructor
	var $rows;		// number of rows per page
    var $linksPerPage=10; // number of links per page in navigation bar
    var $showPageLinks; 

	var $gridAttributes = 'width="100%" border="1" bgcolor="#FFFFFF"';
	
	// Localize text strings here
	var $first = '<code>|&lt;</code>';
	var $prev = '<code>&lt;&lt;</code>';
	var $next = '<code>>></code>';
	var $last = '<code>>|</code>';
	var $moreLinks = '...';
	var $startLinks = '...';
	var $gridHeader = false;
	var $htmlSpecialChars = true;
	var $page = 'Page';
	var $linkSelectedColor = '#FF0000';
	var $cache = 0;  #secs to cache with CachePageExecute()

// sponthus 30/08/2005 
	var $aResult = array();
	var $aId; // renvoie un tableau d'id des enr � afficher
	var $nbEnr; // nombre d'enregistrements
	var $bandeau; // bandeau de pagination
	var $sParam; // param�tres suppl�mentaires � passer par l'url
	var $idSite;
	
	
	// new thao 30/08/2011
	var $showResults = true; // affiches le nombre de r�sultats par d�fauts
	var $showFooter = true; // affiche la page courante
	var $separator = '&nbsp;'; // affiche un s�parateur 
	
	
// sponthus 30/08/2005 

	//----------------------------------------------
	// constructor
	//
	// $db	adodb connection object
	// $sql	sql statement
	// $id	optional id to identify which pager, 
	//		if you have multiple on 1 page. 
	//		$id should be only be [a-z0-9]*
	//
	function Pagination(&$db, $sql, $sParam="", $idSite=null, $id = 'adodb', $showPageLinks = true)
	{
	global $PHP_SELF;
	
		$curr_page = $id.'_curr_page';
		if (empty($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];
		
		$this->sql = $sql;
		$this->id = $id;
		$this->db = $db;
		$this->showPageLinks = $showPageLinks;

		$next_page = $id.'_next_page';	
		
		if (isset($_GET[$next_page])) {
			$_SESSION[$curr_page] = $_GET[$next_page];
		}
		if (empty($_SESSION[$curr_page])) $_SESSION[$curr_page] = 1; ## at first page
		
		$this->curr_page = $_SESSION[$curr_page];
		$this->sParam = $sParam;
		$this->idSite = $idSite;
	}
	
	//---------------------------
	// Display link to first page
	function Render_First($anchor=true)
	{
	global $PHP_SELF;
		if ($anchor) {
	?>
		<a class="cms_first" href="<?php echo $PHP_SELF,'?',$this->id;?>_next_page=1<?php echo $this->sParam; ?>"><?php echo $this->first;?></a> &nbsp; 
	<?php
		} else {
			print "<a class=\"cms_first\">$this->first &nbsp; </a>";
		}
	}
	
	//--------------------------
	// Display link to next page
	function render_next($anchor=true)
	{
	global $PHP_SELF;
	
		if ($anchor) {
		?>
		<a class="cms_next" href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?><?php echo $this->sParam; ?>"><?php echo $this->next;?></a> &nbsp; 
		<?php
		} else {
			print "<a class=\"cms_next\">$this->next &nbsp; </a>";
		}
	}
	
	//------------------
	// Link to last page
	// 
	// for better performance with large recordsets, you can set
	// $this->db->pageExecuteCountRows = false, which disables
	// last page counting.
	function render_last($anchor=true)
	{
	global $PHP_SELF;
	
		if (!$this->db->pageExecuteCountRows) return;
		
		if ($anchor) {
		?>
			<a class="cms_last" href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->LastPageNo() ?><?php echo $this->sParam; ?>"><?php echo $this->last;?></a> &nbsp; 
		<?php
		} else {
			print "<a class=\"cms_last\">$this->last &nbsp; </a>";
		}
	}
	
	//---------------------------------------------------
	// original code by "Pablo Costa" <pablo@cbsp.com.br> 
        function render_pagelinks()
        {
        global $PHP_SELF;
            $pages        = $this->rs->LastPageNo();
            $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
            for($i=1; $i <= $pages; $i+=$linksperpage)
            {
                if($this->rs->AbsolutePage() >= $i)
                {
                    $start = $i;
                }
            }
			$numbers = '';
            $end = $start+$linksperpage-1;
			$link = $this->id . "_next_page";
            if($end > $pages) $end = $pages;
			

// $this->sParam
			
			if ($this->startLinks && $start > 1) {
				$pos = $start - 1;
				$numbers .= "<a href=\"$PHP_SELF?$link=$pos".$this->sParam."\">$this->startLinks</a>  ";
            } 
			
			for($i=$start; $i <= $end; $i++) {
				if ($i != $start) $numbers .=  $this->separator;
                if ($this->rs->AbsolutePage() == $i)
                     $numbers .= "<a href=\"$PHP_SELF?$link=$i".$this->sParam."\" class=\"actif\" >$i</a>  ";
                else 
                     $numbers .= "<a href=\"$PHP_SELF?$link=$i".$this->sParam."\">$i</a>  ";
            
            }
			if ($this->moreLinks && $end < $pages) 
				$numbers .= "<a href=\"$PHP_SELF?$link=$i".$this->sParam."\">$this->moreLinks</a>  ";
            print $numbers . ' &nbsp; ';
        }
	// Link to previous page
	function render_prev($anchor=true)
	{
	global $PHP_SELF;
		if ($anchor) {
	?>
		<a class="cms_prev" href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?><?php echo $this->sParam; ?>"><?php echo $this->prev;?></a> &nbsp; 
	<?php 
		} else {			
			print "<a class=\"cms_prev\">$this->prev &nbsp; </a>";
		}
	}
	
	//--------------------------------------------------------
	// Simply rendering of grid. You should override this for
	// better control over the format of the grid
	//
	// We use output buffering to keep code clean and readable.
	function RenderGrid()
	{
	global $gSQLBlockRows; // used by rs2html to indicate how many rows to display
		include_once(ADODB_DIR.'/tohtml.inc.php');
		ob_start();
		$gSQLBlockRows = $this->rows;
		rs2html($this->rs, $this->gridAttributes, $this->gridHeader, $this->htmlSpecialChars);
		
//		$s = ob_get_contents();
		ob_end_clean();


		// sponthus 30/08/2005
		// modif par rapport � la class de base
		// ici je n'�cris pas le tableau directement
		// je r�cup�re juste les id car j'affiche le tableau sur les pages de listes appelantes

		$aEnr = $this->rs->_array;

$bDebug = false;
if ($bDebug) {
print("<br>sizeof(aEnr)=><strong>".sizeof($aEnr)."</strong><br>");
print("<br>".var_dump($aEnr[0][0]));
print("<br>".var_dump($aEnr));
}

		$aIdEnr = array();
		for ($m=0; $m<sizeof($aEnr); $m++)	$aIdEnr[] = $this->rs->_array[$m][0];

		$s = $aIdEnr;

		return $s;
	}
	
	//-------------------------------------------------------
	// Navigation bar
	//
	// we use output buffering to keep the code easy to read.
	function RenderNav()
	{
		ob_start();

		if (!$this->rs->AtFirstPage()) {
			$this->Render_First();
			$this->Render_Prev();
		} else {
			$this->Render_First(false);
			$this->Render_Prev(false);
		}
        if ($this->showPageLinks){
            $this->Render_PageLinks();
        }
		if (!$this->rs->AtLastPage()) {
			$this->Render_Next();
			$this->Render_Last();
		} else {
			$this->Render_Next(false);
			$this->Render_Last(false);
		}
		$s = ob_get_contents();
		ob_end_clean();
		return $s;
	}
	
	//-------------------
	// This is the footer
	function RenderPageCount()
	{
		if (!$this->db->pageExecuteCountRows) return '';
		$lastPage = $this->rs->LastPageNo();
		if ($lastPage == -1) $lastPage = 1; // check for empty rs.
		if ($this->curr_page > $lastPage) $this->curr_page = 1;
		return $this->page." ".$this->curr_page."/".$lastPage;
	}
	
	//-----------------------------------
	// Call this class to draw everything.
	function Render($rows)
	{
	if (rows=="") $rows = 10;
	global $ADODB_COUNTRECS;
	
		$this->rows = $rows;
		
		if ($this->db->dataProvider == 'informix') $this->db->cursorType = IFX_SCROLL;
		
		$savec = $ADODB_COUNTRECS;

		if ($this->db->pageExecuteCountRows) $ADODB_COUNTRECS = true;


		if ($this->cache)
			$rs = &$this->db->CachePageExecute($this->cache,$this->sql,$rows,$this->curr_page);
		else
			$rs = &$this->db->PageExecute($this->sql,$rows,$this->curr_page);

		if((bool)($rs) == false){
			error_log($this->sql);
		}
		

		//----------------------------------------
		// stockage du r�sultat dans un tableau
		$aResultat = array();
		if ($rs != false){
			while(!$rs->EOF) {	
				array_push($aResultat, $rs->fields[0]);
				$rs->MoveNext();
			}
		}

		$this->aResult = $aResultat;
		//----------------------------------------

// execution de la requete une deuxi�me fois pour replacer le curseur en haut...
		if ($this->db->pageExecuteCountRows) $ADODB_COUNTRECS = true;




		if ($this->cache)
			$rs = &$this->db->CachePageExecute($this->cache,$this->sql,$rows,$this->curr_page);
		else
			$rs = &$this->db->PageExecute($this->sql, $rows, $this->curr_page);

		$ADODB_COUNTRECS = $savec;
		
		$this->rs = &$rs;
		if (!$rs) {
			print '<h3>pagination.class Query failed: '.$this->sql.'</h3>';
			return;
		}
		
		if (!$rs->EOF && (!$rs->AtFirstPage() || !$rs->AtLastPage())) 
			$header = $this->RenderNav();
		else
			$header = "&nbsp;";
		
		$grid = $this->RenderGrid();

// sponthus 30/08/2005
		// tableau d'id r�sultats
		$this->aId = $grid;
		// nombre d'enregistrements
		$this->nbEnr = $this->rs->MaxRecordCount();


		$footer = $this->RenderPageCount();
		$rs->Close();
		$this->rs = false;
		
		$this->bandeau = $this->RenderLayout($this->nbEnr, $header, $grid, $footer, '', $this->idSite);
	}
	
	//------------------------------------------------------
	// override this to control overall layout and formating
	function RenderLayout($nbEnr, $header,$grid,$footer,$attributes='border=1 bgcolor=beige', $idSite)
	{
/*
		echo "<table ".$attributes."><tr><td>",
				$header,
			"</td></tr><tr><td>",
				$grid,
			"</td></tr><tr><td>",
				$footer,
			"</td></tr></table>";
			
*/		

		if ($this->showResults) {
			if (!isset($idSite)) $idSite = 1;
			$oSite = new Cms_site($idSite);
			$oLangue = new Cms_langue($oSite->get_langue());
			$site_langue = strtolower($oLangue->get_libellecourt());
			if ($site_langue == "fr") $content = $nbEnr."&nbsp;r�sultats";
			else if ($site_langue == "en") $content = $nbEnr."&nbsp;results";
			else $content = $nbEnr."&nbsp;r�sultats";
			$content.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if ($this->showFooter) {
			
			$content.= $footer;
			$content.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$content.= $header;

		return($content);

	}
	
	
	 
	
	
	
	
	
	
	
}


?>