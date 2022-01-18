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


class Pagination_array {

	var $id;	// identifie la session de pagination

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
	var $linkSelectedColor = '#FFFFFF';
	var $cache = 0;  #secs to cache with CachePageExecute()

// sponthus 30/08/2005 
	var $aResult = array();
	var $aId; // renvoie un tableau d'id des enr à afficher
	var $nbEnr; // nombre d'enregistrements
	var $bandeau; // bandeau de pagination
	var $sParam; // paramètres supplémentaires à passer par l'url
// sponthus 30/08/2005 

// sponthus 19/12/2005 
	var $aResult_work = array();
	var $firstPage = 0;
	var $lastPage = 0;
// sponthus 19/12/2005 

	//----------------------------------------------
	// constructor
	//
	//
	function __construct($aResult_work, $showPageLinks = true)
	{
	global $PHP_SELF;

		$this->id = "rr";
	
		$curr_page = $this->id.'_curr_page';
		if (empty($PHP_SELF)) $PHP_SELF = $_SERVER['PHP_SELF'];
		
		$this->showPageLinks = $showPageLinks;

		$next_page = $this->id.'_next_page';	
		
		if (isset($_GET[$next_page])) {
			$_SESSION[$curr_page] = $_GET[$next_page];
		}
		if (empty($_SESSION[$curr_page])) $_SESSION[$curr_page] = 1; ## at first page
		
		$this->curr_page = $_SESSION[$curr_page];
		$this->sParam = $sParam;

		// tableau à paginer
		$this->aResult_work = $aResult_work;

//print("<br>PAGINATE[".newSizeOf($this->aResult_work)."]");

		$this->firstPage = 0;
		$this->lastPage = 1;

	}
	
	//---------------------------
	// Display link to first page
	function Render_First($anchor=true)
	{
	global $PHP_SELF;

		if ($anchor) {
	?>
		<a href="<?php echo $PHP_SELF,'?',$this->id;?>_next_page=1<?php echo $this->sParam; ?>" class="pagination"><?php echo $this->first;?></a> &nbsp; 
	<?php
		} else {
//			print "*2*<span class=\"pagination\">$this->first &nbsp; </span>";
		}
	}
	
	//--------------------------
	// Display link to next page
	function render_next($anchor=true)
	{
	global $PHP_SELF;
	
		if ($anchor) {
		?>
		<a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() + 1 ?><?php echo $this->sParam; ?>" class="pagination"><?php echo $this->next;?></a> &nbsp; 
		<?php
		} else {
//			print "*3*<span class=\"pagination\">$this->next &nbsp; </span>";
		}
	}
	
	//------------------
	// Link to last page
	// 
	// last page counting.
	function render_last($anchor=true)
	{
	global $PHP_SELF;
	
		if ($anchor) {
		?>
			<a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->LastPageNo() ?><?php echo $this->sParam; ?>" class="pagination"><?php echo $this->last;?></a> &nbsp; 
		<?php
		} else {
//			print "*4*<span class=\"pagination\">$this->last &nbsp; </span>";
		}
	}
	
	//---------------------------------------------------
	// original code by "Pablo Costa" <pablo@cbsp.com.br> 
        function render_pagelinks()
        {
        global $PHP_SELF;
            $pages        = $this->lastPage;

            $linksperpage = $this->linksPerPage ? $this->linksPerPage : $pages;
            for($i=1; $i <= $pages; $i+=$linksperpage)
            {
                if($this->curr_page >= $i)
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
				$numbers .= "<a href=\"$PHP_SELF?$link=$pos".$this->sParam."\" class=\"pagination\">$this->startLinks</a>  ";
            } 
			
			for($i=$start; $i <= $end; $i++) {
                if ($this->curr_page == $i)
                    $numbers .= "<span class=\"pagination_clic\">".$i."</span>  ";
                else 
                     $numbers .= "<a href=\"$PHP_SELF?$link=$i".$this->sParam."\" class=\"pagination\">$i</a>  ";
            
            }
			if ($this->moreLinks && $end < $pages) 
				$numbers .= "<a href=\"$PHP_SELF?$link=$i".$this->sParam."\" class=\"pagination\">$this->moreLinks</a>  ";

            //print $numbers . ' &nbsp; ';
			return($numbers);
        }

	// Link to previous page
	function render_prev($anchor=true)
	{
	global $PHP_SELF;
		if ($anchor) {
	?>
		<a href="<?php echo $PHP_SELF,'?',$this->id,'_next_page=',$this->rs->AbsolutePage() - 1 ?><?php echo $this->sParam; ?>" class="pagination"><?php echo $this->prev;?></a> &nbsp; 
	<?php 
		} else {
//			print "*1*<span class=\"pagination\">$this->prev &nbsp; </span>";
		}
	}
	

	function is_AtFirstPage()
	{
		if ($this->firstPage == $this->curr_page) return true;
		else return false;
	}

	function is_AtLastPage()
	{
		if ($this->lastPage == $this->curr_page) return true;
		else return false;
	}

	//-------------------------------------------------------
	// Navigation bar
	//
	// we use output buffering to keep the code easy to read.
	function RenderNav()
	{
		if (!is_AtFirstPage) {
			$this->Render_First();
			$this->Render_Prev();
		} else {
			$this->Render_First(false);
			$this->Render_Prev(false);
		}
        if ($this->showPageLinks){
            $this->Render_PageLinks();
        }
		if (!is_AtLastPage) {
			$this->Render_Next();
			$this->Render_Last();
		} else {
			$this->Render_Next(false);
			$this->Render_Last(false);
		}

		return $this->render_PageLinks();
	}
	
	//-------------------
	// This is the footer
	function RenderPageCount()
	{
		$lastPage = $this->lastPage;
		if ($lastPage == -1) $lastPage = 1; // check for empty rs.

		if ($this->curr_page > $lastPage) $this->curr_page = 1;

		return $this->page." ".$this->curr_page."/".$lastPage;
	}
	
	//-----------------------------------
	// Call this class to draw everything.
	function Render($rows=10)
	{
		// calcul du nombre de pages
		if (newSizeOf($this->aResult_work) == 0) $this->lastPage = -1;
		else $this->lastPage = intval(newSizeOf($this->aResult_work) / $rows)+1;


		$rs = $this->aResult_work;

//var_dump($rs);

		//----------------------------------------
		// stockage du résultat dans un tableau
		$aResultat = array();

		$eDebut = ($this->curr_page -1) * $rows;
		$eLimit = $rows;
	
		// indice de comptage des enr lus
		$j=0;

		//print("<br><font color=white>newSizeOf(rs)=[".newSizeOf($rs)."]</font>");

		for ($m=$eDebut; $m<newSizeOf($rs); $m++) {

			if ($j == $eLimit) {
				// sortie de la boucle
				$m = newSizeOf($rs)+1;

			} else {
				$this->aResult[] = $rs[$m];
			}

			$j++;

		}
		//----------------------------------------

//		if (!$rs->EOF && (!$rs->AtFirstPage() || !$rs->AtLastPage())) 
			$header = $this->RenderNav();
//		else
//			$header = "&nbsp;";
		
		// tableau de résultats
		$this->aId = $this->aResult;
		// nombre d'enregistrements
		$this->nbEnr = newSizeOf($rs);

		$footer = $this->RenderPageCount();

		$this->rs = false;

		$this->bandeau = $this->RenderLayout($this->nbEnr, $header, $footer);
	}
	
	//------------------------------------------------------
	// override this to control overall layout and formating
	function RenderLayout($nbEnr, $header, $footer)
	{
		$content = $nbEnr."&nbsp;".$slg_resultats."";
		$content.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$content.= $footer;
		$content.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		$content.= $header;

//print("<br>----<br>");
//var_dump($content);
//print("<br>----<br>");

		return($content);
	}
}


?>