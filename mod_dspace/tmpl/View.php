<?php
class View {
	public function subtype($sub){
		return ucfirst($sub);
	}

	public function html_especial_chars($texto){
		return (htmlspecialchars_decode($texto));
	}
	public function remplace($text){
		return str_replace(" ", S_CONECTOR5, $text);
	}
	public function link_author( $author){
		$link = get_protocol_domain().S_FILTER;
                $name = str_replace(",", S_CONECTOR4, $author);
                $name = $this->remplace($name);
                $link .= strtolower($name). S_SEPARATOR . $name;
		return  ('<a href='.$link.' target="_blank">'.$author.'</a>') ;
	}
	
	public function author($authors){ ?>
            <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_AUTHOR'); ?>
            <?php
                $names = array ();
		foreach ( $authors as $author ) {
                    array_push ($names, "<author><name>".$this->link_author($author->get_name ())."</name></author>");
		}//end foreach autores
            print_r(implode("-", $names));
            ?>
            </div>
            <?php
            return;
	}
	public function is_description($des){
		return  ( ($des == "description" || $des == "summary"  ));
	}
        
	public function show_text($text,$maxlenght){
            if (!is_null($maxlenght) && ($maxlenght!=0)){
		echo ($this->html_especial_chars(substr($text, 0, $maxlenght).'...'));
            }
            else {
               echo  $this->html_especial_chars($text);
            }
            return;
	}
	
	public function show_description ($description,$item,$maxlenght){
		if ($description == "description") {
                        $show_text = $item->get_item_tags(SIMPLEPIE_NAMESPACE_DC_11,'description') ;
                        $show_text = $show_text[0]['data'];
                ?>        
                        <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_DESCRIPTION'); ?>
                <?php            
                } else {
                        $show_text = $item->get_description ();
                ?>        
                        <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_SUMMARY'); ?>
                <?php            
                } ?>
                <span class="sedici-content">
                <?php 
                    $this->show_text($show_text,$maxlenght);
                    ?></span>
                </div>
                <?php
		return;
	}
	
        public function dctype($entry){
		//return subtype document
		$description = $entry->get_description();
		$dctype = explode ( "\n", $description );
		return ($dctype[0]);
	}
        
	public function description($description,$item,$maxlenght){
		if($this->is_description($description)){
			?>
			<summary>
			<?php $this->show_description($description, $item,$maxlenght); ?>
			</summary>
		<?php 
		}
		return;
	}
        function share($link,$title){
        ?>
        <div class="a_unline">
            <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_SHARE'); ?>
             <a href="https://www.facebook.com/sharer/sharer.php?p[title]=<?php echo $title;?>&p[url]=<?php echo $link;?>" target="_blank">
                 <?php echo '<img src="' . JUri::base().'media/mod_dspace/img/share-facebook.png'. '" alt="Facebook logo" title="Compartir en Facebook">';?>
             </a>
             <a href="https://twitter.com/?status=<?php echo $title," ",$link," via @sedici_unlp";?>" target="_blank">
                  <?php echo '<img src="' . JUri::base().'media/mod_dspace/img/share-twitter.png'. '" alt="Twitter logo" title="Compartir en Twitter">';?>
             </a>
             <a href="https://plus.google.com/share?url=<?php echo $link;?>" target="_blank">
                  <?php echo '<img src="' . JUri::base().'media/mod_dspace/img/share-plus.png'. '" alt="Google+ logo" title="Compartir en Google+">';?>
             </a>
             <a href="http://www.linkedin.com/shareArticle?url=<?php echo $link;?>" target="_blank">
                  <?php echo '<img src="'. JUri::base().'media/mod_dspace/img/share-linkedin.png'. '" alt="Linkedin logo" title="Compartir en Linkedin">';?>
             </a>
            </div>    
        </div>    
        <?php    
        }
	public function document($item,$attributes){
		$link = $item->get_link ();	
		?>
		<article>
			<title><?php echo $item->get_title ();?></title>
                        <div id="sedici-title">
                            <li><a href="<?php echo $link; ?>" target="_blank">
                            <?php echo ($this->html_especial_chars($item->get_title ())); ?> 
                            </a></li>
                        </div>  
				<?php 
				if ($attributes['show_author']){ $this->author($item->get_authors ()); }
				if ($attributes['date']) 
                                { ?>
                                    <published>
                                        <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_DATE'); ?> 
                                        <span class="sedici-content"><?php  echo $item->get_date ( 'Y-m-d' ); ?></span></div>
                                    </published>
				<?php } //end if fecha  
                                if ($attributes['show_subtypes']) 
                                { ?>
                                    <dc:type>
                                        <div id="sedici-title"><?php echo JText::_('MOD_DSPACE_VIEW_DOCUMENT_SUBTYPE'); ?> 
                                            <span class="sedici-content"><?php  echo $this->dctype($item); ?></span></div>
                                    </dc:type>
				<?php } //end if fecha
				$this->description($attributes['description'], $item,$attributes['max_lenght']);
                                if ($attributes['share']){ $this->share($link,$item->get_title ()); }
				?>
		</article>
		<?php 
		return;
	}
	
	public function publicationsByDateSubtype($entrys, $attributes) {
                    $date="";$subtype="";
                    ?><ul><?php
			foreach ($entrys as $item){
                            $date2=$date;
                            $date=$item->get_date ( 'Y' );
                            $subtype2=$subtype;
                            $subtype= $this->dctype($item);
                            if($date != $date2) { echo "<h2>".$date."</h2>";
                            $subtype2="";
                            }
                            if($subtype != $subtype2) { echo "<h3>".$subtype."</h3>";}
                            $this->document($item, $attributes);
			}
                    ?></ul><?php    
            return ;
	}
        public function group($item,$group){
            if ($group == "date") {
                return $item->get_date ( 'Y' );
            } else if ( $group == "subtype") {
                return $this->dctype($item);
            }
        }
        
        public function publicationsByGroup($entrys, $attributes, $group) {
                    $order="";
                    ?><ul><?php
			foreach ($entrys as $item){
                            $value=$order;
                            $order= $this->group($item, $group);
                             if($value != $order) {
                                 ?><h2><?php echo $order; ?></h2><?php
                             }
                            $this->document($item, $attributes);
			}
                    ?></ul><?php    
            return ;
	}
        
        
        public function allPublications($entrys, $attributes) {
                ?><ul><?php
			foreach ($entrys as $item){
                            $this->document($item, $attributes);
			}
                ?></ul><?php
            return ;
	}
        public function render ($results,$attributes,$group_subtype,$group_date){
                if ($group_date && $group_subtype) {
                    return ($this->publicationsByDateSubtype ( $results, $attributes));
                }
                if ($group_date){
                     return ($this->publicationsByGroup( $results, $attributes,"date"));
                }
                if ($group_subtype){
                     return ($this->publicationsByGroup( $results, $attributes,"subtype"));
                }
                return $this->allPublications($results, $attributes);
	}
} // end class