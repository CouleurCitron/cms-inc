<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/include/autoprepend.php');


// Classe pour extraire les contenus de la boutique vers un moteur de rendu


// needs to extend BaseModuleModel
include_once($_SERVER['DOCUMENT_ROOT'].'/include/cms-inc/mod_base/class.BaseModuleModel.php');


class WebShopModel extends BaseModuleModel {

	// constructor
	function WebShopModel () {}

	// segment
	/**
	 * get tree path to get to a given node
	 * recurse from bottom to top
	 *
	 * @param	Int	$id_gamme		a node ID
	 * @return	Array		parent nodes path to the given node
	 */
	function segment ($id_gamme) {
		$pile = $this->recurse_segment($id_gamme, Array());

		return (Array) array_reverse($pile);
	}

	function recurse_segment($id_gamme, $pile) { 
		$gamme = dbGetObjectFromPK('shp_gamme', $id_gamme);
		 
		$pile[] = Array(		'id'		=>	$id_gamme,
			    		'name'		=>	$gamme->get_titre_court(),
			    		'long_name'	=>	$gamme->get_titre_long(),
			    		'subtitle'	=>	$gamme->get_sous_titre(),
			    		'description'	=>	$gamme->get_texte_long(),
			    		'delivery'	=>	$gamme->get_delai_livraison(),
			    		'thumbnail'	=>	$gamme->get_vignette(),
			    		'image'		=>	$gamme->get_visuel(),
			    		'color'		=>	$gamme->get_couleur_hex() );
		if ($gamme->get_id_gamme() > 0)
			$pile = $this->recurse_segment($gamme->get_id_gamme(), $pile);
	    	return (Array) $pile;
	}

	// retrieve
	/**
	 * get subtree for a given root or all tree if none was given
	 * recurse from top to bottom
	 *
	 * @param	Int	$id_root		a root node ID
	 * @param	Bool	$top_only	retrieve only top level nodes
	 * @return	Array		sub nodes structure from the given node
	 */
	function retrieve ($id_root=null, $top_only=false) {
		
		$pile = Array();

		if (is_null($id_root)) {  
			$res = dbGetObjectsFromFieldValue2('shp_gamme', Array('get_id_gamme', 'get_statut'), Array(-1, DEF_ID_STATUT_LIGNE), Array('get_ordre'), Array('DESC'));
			if (count($res) > 0) {
				foreach ($res as $root)
					$pile[] = $this->recursiveDig($root, $top_only);
			}
		} else { 
			$gamme = dbGetObjectFromPK('shp_gamme', $id_root);
			$pile[] = $this->recursiveDig($gamme);
		}

		return (Array) $pile;
	}


	// recursiveDig
	/**
	 * Retrieve sub tree nodes with recursive method
	 *
	 * @param	Array		$parent		record data of the parent node of the children we want to retrieve
	 * @param	Bool		$stop		stop at this level
	 * @return	Void
	 */
	function recursiveDig ($gamme, $stop=false) {
		// translation engine
		$translator =& TslManager::getInstance();

		$level = Array(	'id'		=> $gamme->get_id(),
				'titre_court'	=> $gamme->get_titre_court(),
				'titre_long'	=> $gamme->get_titre_long(),
				'sous_titre'	=> $gamme->get_sous_titre(),
				'texte_long'	=> $gamme->get_texte_long(),
				'diaporama'	=> $gamme->get_id_diaporama(),
				'couleur_hex'	=> $gamme->get_couleur_hex(),
				'vignette'	=> $gamme->get_vignette(),
				'visuel'		=> $gamme->get_visuel() ); 
		if ($gamme->get_id_gamme() > 0){
			$gam = new shp_gamme($gamme->get_id_gamme());
			$level['parent'] = $gam->get_titre_court();
		}
				
		$res = dbGetObjectsFromFieldValue2('shp_gamme', Array('get_id_gamme', 'get_statut'), Array($gamme->get_id(), DEF_ID_STATUT_LIGNE), Array('get_ordre'), Array('DESC'));
		if (!$stop && count($res) > 0) {
			$level['children'] = Array();
			foreach ($res as $child)
				$level['children'][] = $this->recursiveDig($child);
		}

		return (Array) $level;
	}


	// extract
	/**
	 * get product info for a given product section
	 *
	 * @param	Int		$id_gamme		The product section
	 * @return	Array	product pile
	 */
	function extract ($id_gamme = -1) {
		 
		global $db;

		// comptabiliser par rapport aux tarifs, même si cette extraction ne sert pas à les afficher
		// si pas de tarif défini => pas de produit affiché...
		// un tarif standard en statut en-ligne aura toujours la priorité
		if ($id_gamme != -1) {
		$sql = "	SELECT	p.*,
				pt.shp_typ_titre_court as prod_type,
				u.shp_uni_code as unit,
				t.shp_trf_prix as price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p,
				`shp_produit_type` pt,
				`shp_tarif` t,
				`shp_unite` u
			WHERE	p.shp_pdt_id_type = pt.shp_typ_id
			AND	p.shp_pdt_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_produit = p.shp_pdt_id
			AND	p.shp_pdt_id_gamme = {$id_gamme}
			AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE."
			";
		}
		else {
				$sql = "	SELECT	p.*,
				pt.shp_typ_titre_court as prod_type,
				u.shp_uni_code as unit,
				t.shp_trf_prix as price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p,
				`shp_produit_type` pt,
				`shp_tarif` t,
				`shp_unite` u
			WHERE	p.shp_pdt_id_type = pt.shp_typ_id
			AND	p.shp_pdt_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_produit = p.shp_pdt_id 
			AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE."
			";
		}
			
		
			
		if (WEBSHOP_STOCK_DEDUCE)
			$sql .= "AND	p.shp_pdt_quantite_stock > 0
			";
		$sql .= "ORDER BY	p.shp_pdt_ordre DESC;";
		
		
		//print ($sql);	
		
		
		if ($this->debug)
			echo 'WebShopModel.extract > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		$pile = array();
		$std_pile = Array();
		if ($rs) {
			while (!$rs->EOF) {
				$type = $rs->fields['shp_pdt_id_type'];
				$id = $rs->fields['shp_pdt_id'];

				if (empty($pile[$type]))
					$pile[$type] = Array();
				
				if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] != DEF_ID_STATUT_LIGNE)
					$std_pile[$type][$id] = $rs->fields['price'];

				if (!empty($pile[$type][$id])) {
					// already found
					if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// standard and active will bypass others
						$pile[$type][$id]['price'] = $rs->fields['price'];
						$pile[$type][$id]['std_price'] = $rs->fields['price'];
					}

				} else {
					// brand new
					if ($rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// active
						$pile[$type][$id] = $rs->fields;

						if ($rs->fields['standard'] == 'Y')
							// and standard
							$pile[$type][$id]['std_price'] = $rs->fields['price'];
					}

				}
				unset($pile[$type][$id]['standard']);
				unset($pile[$type][$id]['trf_status']);

				if (!empty($pile[$type][$id]) && !empty($std_pile[$type][$id]))
					$pile[$type][$id]['std_price'] = $std_pile[$type][$id];

				$rs->MoveNext();
				//viewArray($pile, 'PILE');
				//viewArray($std_pile, 'STD_PILE');
			}
		}
		//viewArray($pile);
		return (array) $pile;
	}


	// getProductDetails
	/**
	 * get product info for a given product
	 *
	 * @param	Int		$id_produit		The product ID
	 * @return	Array	product details table
	 */
	function getProductDetails ($id_produit) {
		global $db;

		$sql = "	SELECT	p.shp_pdt_id as id,
				p.shp_pdt_id_gamme as id_gamme,
				p.shp_pdt_id_diaporama as id_diaporama,
				p.shp_pdt_vignette as picto,
				p.shp_pdt_pieces_unite as cnt,
				pt.shp_typ_titre_court as type,
				u.shp_uni_code as unit,
				t.shp_trf_prix as price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p,
				`shp_produit_type` pt,
				`shp_tarif` t,
				`shp_unite` u
			WHERE	p.shp_pdt_id_type = pt.shp_typ_id
			AND	p.shp_pdt_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_produit = p.shp_pdt_id
			AND	p.shp_pdt_id = {$id_produit}
			AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE.";";

		if ($this->debug)
			echo 'WebShopModel.getProductDetails > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		$pile = Array();
		$std_pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
				$id = $rs->fields['shp_pdt_id'];

				if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] != DEF_ID_STATUT_LIGNE)
					$std_pile[$id] = $rs->fields['price'];

				if (!empty($pile[$id])) {
					// already found
					if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// standard and active will bypass others
						$pile[$id]['price'] = $rs->fields['price'];
						$pile[$id]['std_price'] = $rs->fields['price'];
					}

				} else {
					// brand new
					if ($rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// active
						$pile[$id] = $rs->fields;

						if ($rs->fields['standard'] == 'Y')
							// and standard
							$pile[$id]['std_price'] = $rs->fields['price'];
					}

				}
				unset($pile[$id]['standard']);
				unset($pile[$id]['trf_status']);

				if (!empty($pile[$id]) && !empty($std_pile[$id]))
					$pile[$id]['std_price'] = $std_pile[$id];
				$rs->MoveNext();
			}
		}
		//viewArray($pile);
		return (Array) $pile;
	}
	
	
	// getProductDetailsWithoutType
	/**
	 * get product info for a given product
	 *
	 * @param	Int		$id_produit		The product ID
	 * @return	Array	product details table
	 */
	function getProductDetailsWithoutType ($id_produit) {
		global $db;

		$sql = "	SELECT	p.*,
				p.shp_pdt_pieces_unite as cnt, 
				u.shp_uni_code as unit,
				t.shp_trf_prix as price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p, 
				`shp_tarif` t,
				`shp_unite` u
			WHERE	p.shp_pdt_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_produit = p.shp_pdt_id
			AND	p.shp_pdt_id = {$id_produit}
			AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE.";";

		if ($this->debug)
			echo 'WebShopModel.getProductDetailsWithoutType > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		$pile = Array();
		$std_pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
				$id = $rs->fields['shp_pdt_id'];

				if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] != DEF_ID_STATUT_LIGNE)
					$std_pile[$id] = $rs->fields['price'];

				if (!empty($pile[$id])) {
					// already found
					if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// standard and active will bypass others
						$pile[$id]['price'] = $rs->fields['price'];
						$pile[$id]['std_price'] = $rs->fields['price'];
					}

				} else {
					// brand new
					if ($rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// active
						$pile[$id] = $rs->fields;

						if ($rs->fields['standard'] == 'Y')
							// and standard
							$pile[$id]['std_price'] = $rs->fields['price'];
					}

				}
				unset($pile[$id]['standard']);
				unset($pile[$id]['trf_status']);

				if (!empty($pile[$id]) && !empty($std_pile[$id]))
					$pile[$id]['std_price'] = $std_pile[$id];
				$rs->MoveNext();
			}
		}
		//viewArray($pile);
		return (Array) $pile;
	}


	// retrieveHomeExcerpt
	/**
	 * get homepage product excerpt
	 * One or more products get extracted for homepage or custom display
	 *
	 * @param	Bool		$single		Extract only one
	 * @return	Object		a shp_product instance for display
	 */
	function retrieveHomeExcerpt ($single=false) {
		global $db;

		$pile = Array();

		$sql = "	SELECT	p.shp_pdt_id as id,
				p.shp_pdt_id_gamme as id_gamme,
				p.shp_pdt_id_diaporama as id_diaporama,
				p.shp_pdt_titre_court as title,
				p.shp_pdt_vignette as picto,
				p.shp_pdt_visuel as image,
				p.shp_pdt_pieces_unite as cnt,
				pt.shp_typ_titre_court as type,
				u.shp_uni_code as unit,
				t.shp_trf_prix as price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p,
				`shp_produit_type` pt,
				`shp_tarif` t,
				`shp_unite` u
			WHERE	p.shp_pdt_id_type = pt.shp_typ_id
			AND	p.shp_pdt_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_unite = u.shp_uni_id
			AND	t.shp_trf_id_produit = p.shp_pdt_id
			AND	p.shp_pdt_remontee = 'Y'
			AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE."
			ORDER BY	p.shp_pdt_rem_ordre DESC;";

		if ($this->debug)
			echo 'WebShopModel.retrieveHomeExcerpt > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		$pile = Array();
		$std_pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
				$id = $rs->fields['id'];

				if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] != DEF_ID_STATUT_LIGNE)
					$std_pile[$id] = $rs->fields['price'];

				if (!empty($pile[$id])) {
					// already found
					if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// standard and active will bypass others
						$pile[$id]['price'] = $rs->fields['price'];
						$pile[$id]['std_price'] = $rs->fields['price'];
					}

				} else {
					// brand new
					if ($rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// active
						$pile[$id] = $rs->fields;

						if ($rs->fields['standard'] == 'Y')
							// and standard
							$pile[$id]['std_price'] = $rs->fields['price'];
					}

				}
				unset($pile[$id]['standard']);
				unset($pile[$id]['trf_status']);

				if (!empty($pile[$id]) && !empty($std_pile[$id]))
					$pile[$id]['std_price'] = $std_pile[$id];
				$rs->MoveNext();
			}
		}
		if ($single) {
			$key = array_shift(array_keys($pile));
			return (Array) $pile[$key];
		} else	return (Array) $pile;
	}


	// getProductDiaporama
	/**
	 * get product diaporama images list
	 *
	 * @param	Int		$id_diaporama		The diaporama ID
	 * @return	Array	product diaporama images
	 */
	function getProductDiaporama ($id_diaporama) {
		global $db;

		$pile = Array();
		$sql = "	SELECT	d.dia_id,
				d.dia_nom as nom,
				d.dia_image as image,
				d.dia_viewer as viewer,
				i.img_id,
				i.img_titre as titre,
				i.img_src as src,
				i.img_vignette as vignette,
				i.img_metadata as metadate
			FROM	`cms_diaporama` d,
				`cms_assodiapodiaporama` x,
				`cms_diapo` i
			WHERE	x.xdp_cms_diaporama = d.dia_id
			AND	x.xdp_cms_diapo = i.img_id
			AND	d.dia_id = {$id_diaporama}
			AND	d.dia_statut = ".DEF_ID_STATUT_LIGNE."
			AND	i.img_statut = ".DEF_ID_STATUT_LIGNE.";";

		if ($this->debug)
			echo 'WebShopModel.getProductDiaporama > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		if ($rs) {
			while(!$rs->EOF) {
				$pile[] = $rs->fields;
				$rs->MoveNext();
			}
		}
		return (Array) $pile;
	}
	
	
	// getProductsProps
	/**
	 * get product info from a product ID list
	 *
	 * @param	Array		$ids		The product IDs
	 * @return	Array	product pile
	 */
	function getProductsProps ($ids) {
		global $db;
		$pile = array();
	
		if (!is_array($ids)){
			$ids = array($ids);
		}

		$sql = "	SELECT	p.*,
				t.shp_trf_prix AS price,
				t.shp_trf_statut as trf_status,
				t.shp_trf_standard as standard
			FROM	`shp_produit` p,
				`shp_tarif` t
			WHERE	t.shp_trf_id_produit = p.shp_pdt_id
			AND	t.shp_trf_id_unite = p.shp_pdt_id_unite";
		if (count($ids)>0){
			$sql .= "	AND	p.shp_pdt_id IN (".implode(',', $ids).")";
		}
		$sql .= "	AND	p.shp_pdt_statut = ".DEF_ID_STATUT_LIGNE.";";

		if ($this->debug)
			echo 'WebShopModel.getProductsProps > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		$std_pile = Array();
		if ($rs) {
			while(!$rs->EOF) {
//				$prods[$rs->fields['shp_pdt_id']] = $rs->fields;
//				$rs->MoveNext();
				$id = $rs->fields['shp_pdt_id'];

				if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] != DEF_ID_STATUT_LIGNE)
					$std_pile[$id] = $rs->fields['price'];

				if (!empty($pile[$id])) {
					// already found
					if ($rs->fields['standard'] == 'Y' && $rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// standard and active will bypass others
						$pile[$id]['price'] = $rs->fields['price'];
						$pile[$id]['std_price'] = $rs->fields['price'];
					}

				} else {
					// brand new
					if ($rs->fields['trf_status'] == DEF_ID_STATUT_LIGNE) {
						// active
						$pile[$id] = $rs->fields;

						if ($rs->fields['standard'] == 'Y')
							// and standard
							$pile[$id]['std_price'] = $rs->fields['price'];
					}

				}
				unset($pile[$id]['standard']);
				unset($pile[$id]['trf_status']);

				if (!empty($pile[$id]) && !empty($std_pile[$id]))
					$pile[$id]['std_price'] = $std_pile[$id];
				$rs->MoveNext();
			}
		}
		return (Array) $pile;
	}


	// getAssociatedSections
	/**
	 * get associated sections for a given product section
	 *
	 * @param	int		$id_gamme		The product section ID
	 * @return	Array	product section pile
	 */
	function getAssociatedSections ($id_gamme) {
		global $db;
		$sections = Array();
		$sql = "	SELECT	g.*
			FROM	`shp_gamme` g,
				`shp_asso_gammes` asso
			WHERE	(asso.shp_xgg_id_gamme1 = g.shp_gam_id AND asso.shp_xgg_id_gamme2 = {$id_gamme})
			OR	(asso.shp_xgg_id_gamme1 = {$id_gamme} AND asso.shp_xgg_id_gamme2 = g.shp_gam_id);
			AND	g.shp_gam_statut = 4";

		if ($this->debug)
			echo 'WebShopModel.getAssociatedSections > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		if ($rs) {
			while(!$rs->EOF) {
				$sections[$rs->fields['shp_pdt_id']] = $rs->fields;
				$rs->MoveNext();
			}
		}
		return (Array) $sections;
	}


	// getAssociatedProducts
	/**
	 * get associated products for a given product
	 *
	 * @param	int		$id_produit		The product ID
	 * @return	Array	product pile
	 */
	function getAssociatedProducts ($id_produit) {
		global $db;
		$prods = Array();
		
		$all = true ;
			
		if (defined("WEBSHOP_ASSO_PRODUCT_ALL") ) {
		
			if (!WEBSHOP_ASSO_PRODUCT_ALL) {
			
				$all = false ;
				
			}
			
		}
			
			
		if (WEBSHOP_STOCK_ON_PAY) {
			 
			
			if ($all) {
				$sql = "	SELECT	p.*,
						t.shp_trf_prix AS price
					FROM	`shp_produit` p,
						`shp_tarif` t,
						`shp_asso_produits` asso
					WHERE	t.shp_trf_id_produit = p.shp_pdt_id
					AND	t.shp_trf_id_unite = p.shp_pdt_id_unite
					AND	(asso.shp_xpp_id_produit1 = {$id_produit} AND asso.shp_xpp_id_produit2 = p.shp_pdt_id) OR (asso.shp_xpp_id_produit1 = p.shp_pdt_id AND asso.shp_xpp_id_produit2 = {$id_produit}));
					AND	p.shp_pdt_statut = 4
					AND	p.shp_pdt_quantite_stock > 0
					";
			}
			else {
				$sql = "	SELECT	p.*,
						t.shp_trf_prix AS price
					FROM	`shp_produit` p,
						`shp_tarif` t,
						`shp_asso_produits` asso
					WHERE	t.shp_trf_id_produit = p.shp_pdt_id
					AND	t.shp_trf_id_unite = p.shp_pdt_id_unite
					AND	(asso.shp_xpp_id_produit1 = {$id_produit} AND asso.shp_xpp_id_produit2 = p.shp_pdt_id );
					AND	p.shp_pdt_statut = 4
					AND	p.shp_pdt_quantite_stock > 0
					";
			}
			 
		}
		else {
			
			if ($all) {
				$sql = "	SELECT	p.*,
					t.shp_trf_prix AS price
				FROM	`shp_produit` p,
					`shp_tarif` t,
					`shp_asso_produits` asso
				WHERE	t.shp_trf_id_produit = p.shp_pdt_id
				AND	t.shp_trf_id_unite = p.shp_pdt_id_unite
				AND	(asso.shp_xpp_id_produit1 = {$id_produit} AND asso.shp_xpp_id_produit2 = p.shp_pdt_id) OR (asso.shp_xpp_id_produit1 = p.shp_pdt_id AND asso.shp_xpp_id_produit2 = {$id_produit}) 
				AND	p.shp_pdt_statut = 4 ";
			}
			else {
				$sql = "	SELECT	p.*,
					t.shp_trf_prix AS price
				FROM	`shp_produit` p,
					`shp_tarif` t,
					`shp_asso_produits` asso
				WHERE	t.shp_trf_id_produit = p.shp_pdt_id
				AND	t.shp_trf_id_unite = p.shp_pdt_id_unite
				AND	(asso.shp_xpp_id_produit1 = {$id_produit} AND asso.shp_xpp_id_produit2 = p.shp_pdt_id) 
				AND	p.shp_pdt_statut = 4 ";
			}
		}
		 
		

		if ($this->debug)
			echo 'WebShopModel.getAssociatedProducts > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		if ($rs) {
			while(!$rs->EOF) {
				$prods[$rs->fields['shp_pdt_id']] = $rs->fields;
				$rs->MoveNext();
			}
		}
		return (Array) $prods;
	}
	

	// getAssociatedProductsBySegment
	/**
	 * get associated products for a given segment
	 *
	 * @param	int		$id_gamme		The product ID
	 * @return	Array	product pile
	 */
	function getAssociatedProductsBySegment ($id_gamme) {
		global $db;
		$prods = Array();
		$sql = "	SELECT	p.shp_pdt_id, 	p.shp_pdt_reference, p.shp_pdt_titre_court 
			FROM	`shp_produit` p, 
				`shp_asso_produitgamme` asso
				WHERE	 asso.shp_gamme = {$id_gamme} AND asso.shp_produit = p.shp_pdt_id
			AND	p.shp_pdt_statut = 4
			AND	p.shp_pdt_quantite_stock > 0
			ORDER BY	p.shp_pdt_ordre
			";

		if ($this->debug)
			echo 'WebShopModel.getAssociatedProductsBySegment > '.$sql.'<br/>';
		$rs = $db->Execute($sql);
		if ($rs) {
			while(!$rs->EOF) {
				$prods[$rs->fields['shp_pdt_id']] = $rs->fields;
				$rs->MoveNext();
			}
		}
		return (Array) $prods;
	}


}

?>
