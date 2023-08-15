<?php

class ModifyExtension extends Minz_Extension {

    private $feeds;
    private $cats;

    public function init() {

	$this->registerHook('entry_before_insert', array($this, 'replaceStuff'));
	#$this->registerController('readable');
	#Minz_View::appendScript($this->getFileUrl('jquery.js', 'js'));
	$this->registerViews();
	Minz_View::appendScript($this->getFileUrl('nyanya.js', 'js'));
		

    }
	// https://rss.eris.cc/i/?c=feed&a=contentSelectorPreview&id=16&selector=selector-token&selector_filter=selector-filter-token#slider
    public function getConfigOption() {
	return "<li class=\"configure open-slider\"><a href=\"./?c=readable&a=readableConfigure&from=normal#slider\">Readable</a></li>";
    }
	

    public function replaceStuff($entry) {
	
	$this->loadConfigValues();
	$id = $entry->toArray()['id_feed'];
	$linkS = json_decode(FreshRSS_Context::$user_conf->linkStore);

	if ( array_key_exists($id, $linkS) ) {
		$entry->_link(preg_replace($linkS[$id][0], $linkS[$id][1], $entry->link()));
	}

	return $entry;
    }

    /*
     * These are called from configure.phtml, which is controlled by handleConfigureAction(), 
     * thus values are already fetched from userconfig and FeedDAO.
     */

    public function getCats() {
	    return $this->cats;
    }

    public function getConf() {
	    return $this->linkStore;
    }

    public function getNamebyId($id ) {
	   return $this->feeds[$id]->name();
    }

    /*
    Loading basic variables from user storage
    */
    public function loadConfigValues()
    {
        if (!class_exists('FreshRSS_Context', false) || null === FreshRSS_Context::$user_conf) {
            return;
	}

        if (FreshRSS_Context::$user_conf->linkStore != '') {
            $this->linkStore = FreshRSS_Context::$user_conf->linkStore;
	} else {
	    $this->linkStore = "";
	}
    }

    /*
     * handleConfigureAction() is only executed on loading and saving the extenstion's configuration page.
     * If the Request type is POST, values are being saved. It looks weird, but I copied it from another example and it works flawlessly.
     */
    public function handleConfigureAction()
    {
	$feedDAO = FreshRSS_Factory::createFeedDao();
	$catDAO = FreshRSS_Factory::createCategoryDao();
	$this->feeds = $feedDAO->listFeeds();
	$this->cats = $catDAO->listCategories(true,false); 

	if (Minz_Request::isPost()) {
            $cstore = [];
	    $params = Minz_Request::params();
	    foreach ( $params as $k => $v ) {
		    if (strncmp($k,"regex_reg_",10) === 0 && $v !== "" ){
			    $cid = substr( $k, 10 );
			    if (!array_key_exists($cid, $cstore)){
				$rep = $params["regex_rep_".$cid]; 
				$cstore[intval($cid)] = [$v, $rep];
			    }
		    }
	    }
	    // I don't know if it's possible to save arrays, so it's encoded with json
	    FreshRSS_Context::$user_conf->linkStore = (string)json_encode($cstore);
	    FreshRSS_Context::$user_conf->save();
	}

	$this->loadConfigValues();
    }
}
