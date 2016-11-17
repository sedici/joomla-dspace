<?php
class View {
        protected $configuration;
        public function set_configuration($config){
            $this->configuration = $config;
        }
        public function html_especial_chars($texto){
		return (htmlspecialchars_decode($texto));
	}
	
	public function link_author( $author){
            return $this->configuration->print_author($author);
	}
	
	public function author($authors){ 
            $names = array ();
            foreach ( $authors as $author ) {
            if( isset($author ) && ($author != FALSE)){
            //    if(!empty($author->get_name ())){
                    array_push ($names, "<author><name>".$this->link_author($author->get_name ())."</name></author>");
                }
            }//end foreach autores
            if (!empty($names)){
            ?>
            <div id="sedici-title">
            <?php echo JText::_('MOD_DSPACE_VIEW_AUTHOR');
                print_r(implode("-", $names));
            ?>
            </div>
            <?php
            }
            return;
	}
	public function is_description($des){
		return  ( ($des == "description" || $des == "summary"  ));
	}
        
	public function show_text($text,$maxlenght){
            if (!is_null($maxlenght)){
		echo ($this->html_especial_chars(substr($text, 0, $maxlenght).'...'));
            }
            else {
               echo  $this->html_especial_chars($text);
            }
            return;
	}
	
	public function show_description ($description,$item,$maxlenght){
		if ($description == "description") { ?>
                <div id="sedici-title"><?php echo JText::_("MOD_DSPACE_VIEW_DESCRIPTION");  
                        $show_text = $item->get_item_tags(SIMPLEPIE_NAMESPACE_DC_11,'description') ;
                        $show_text = $show_text[0]['data'];
                } else {  ?>
                    <div id="sedici-title"><?php echo JText::_("MOD_DSPACE_VIEW_SUMMARY"); 
                        $show_text = $item->get_description ();
                } ?>
                <span class="sedici-content">
                <?php 
                    $this->show_text($show_text,$maxlenght);
                    ?>
                </span></div>
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
		<li><article>
			<title><?php echo $item->get_title ();?></title>
                        <div id="sedici-title">
                            <a href="<?php echo $link; ?>" target="_blank">
                            <?php echo ($this->html_especial_chars($item->get_title ())); ?> 
                            </a>
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
		</article></li>
		<?php 
		return;
	}
	
        public function group($item,$group){
            if ($group == "date") {
                return $item->get_date ( 'Y' );
            } else if ( $group == "subtype") {
                return $this->dctype($item);
            }
            return true;
        }
        public function corte($elem,$comparator,$value){
            if($comparator=="date"){
                return ($elem->get_date ( 'Y' )==$value);
            }
            elseif ($comparator=="subtype") {
                return ($this->dctype($elem)==$value);
            }
            return true;
        }
        public function corteControl($anArray,$attributes,$position,$corte,$corte2=""){
            $c=true; $c2=true; 
            $condition= $this->group($anArray[$position],$corte);
            $condition2= $this->group($anArray[$position],$corte2);
            while ( ($position != count($anArray)) && ( $c ) && ($c2)) {
                 $c = $this->corte($anArray[$position], $corte, $condition);
                 $c2 = $this->corte($anArray[$position], $corte2, $condition2);
                 if (($c) && ($c2)) {
                    $this->document($anArray[$position], $attributes);
                    $position++;
                 }   
             }
             return $position;
        }

        public function publicationsByGroup($entrys, $attributes, $group) {
                    $position=0;
                    ?>
                    <div class="documents" id="<?php echo $group; ?>">
                    <?php    
                    while ($position != count($entrys)){
                        $currentElem= $entrys[$position];
                        $title = $this->group($currentElem, $group);
                    ?>
                        <div id="<?php echo $title; ?>">
                        <h2><?php echo $title; ?></h2>
                        <ul>
                    <?php
                        $position = $this->corteControl($entrys,$attributes,$position,$group);
                    ?>
                        </ul>
                        </div>
                    <?php    
                    }
                    ?>
                    </div> <!-- end div=group-->  
            <?php        
            return ;
	}
        public function printTitle($title,$lastTitle){
            if (strcmp($title,$lastTitle)!== 0) { ?>
                <!-- Div open in function printTitle  -->
                <div id="<?php echo $title; ?>">
                <h2><?php echo $title; ?></h2>
            <?php
            }//end if
            return ;
        }
        
        public function closeDiv($actualTitle,$entrys,$position,$group){
            if ($position < count($entrys)) {
                $titleEntry = $this->group($entrys[$position], $group); 
                return (strcmp($actualTitle, $titleEntry)!== 0);
            }
            return true;
        }
        
        public function publicationsByDateSubtype($entrys, $attributes,$group,$subgroup) {
           $position=0; $title="";
           ?>
           <div class="documents" id="DateSubtype">
           <?php    
           while ($position != count($entrys)){
                $currentElem= $entrys[$position];
                $lastTitle = $title;
                $title = $this->group($currentElem, $group);
                $subtitle = $this->group($currentElem, $subgroup);
                $this->printTitle($title, $lastTitle);
            ?>
                <div id="<?php echo $title.$subtitle; ?>">
                <h3><?php echo $subtitle; ?></h3>
                <ul>
            <?php
                $position = $this->corteControl($entrys,$attributes,$position,$group,$subgroup);
            ?>
                </ul>
                </div>    
            <?php
                if($this->closeDiv($title, $entrys, $position, $group)){ 
                    ?>
                    </div> 
                    <!-- Close the Div open in function printTitle  -->
            <?php    
                }// end if(cerrarDiv)
            }//end while
            ?>
            </div> <!-- close div=DateSubtype -->
            <?php    
            return ;
	}
        
        
        public function allPublications($entrys, $attributes) {
            ?><div class="documents" id="allPublications"><ul><?php
			foreach ($entrys as $item){
                            $this->document($item, $attributes);
			}
            ?></ul></div><?php
            return ;
	}
        public function render ($results,$attributes, $cmp, $configuration){
            $this->set_configuration($configuration);
                if(strcmp($cmp, CMP_DATE_SUBTYPE)==0){
                    return ($this->publicationsByDateSubtype ( $results, $attributes,ACTIVE_DATE,ACTIVE_SUBTYPE));
                }
                if (strcmp($cmp, CMP_DATE)==0){
                     return ($this->publicationsByGroup( $results, $attributes,ACTIVE_DATE));
                }
                if (strcmp($cmp, CMP_SUBTYPE)==0){
                     return ($this->publicationsByGroup( $results, $attributes,ACTIVE_SUBTYPE));
                }
                return $this->allPublications($results, $attributes);
        }
} // end class