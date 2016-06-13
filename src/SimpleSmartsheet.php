<?php

class SimpleSmartsheet {
    
    /* Properties */
    private $Gateway = "https://api.smartsheet.com/";
    private $APIVersion = "2.0";
    private $APIKey;
    private $CurrentSheet;
    private $CurrentSheetObject;

    public function __construct($APIKey) {
        $this->APIKey = $APIKey;
        $this->setGateway();
    }
    
    /* API Settings */
    
    public function setGateway($Gateway=null) {
        if (!empty($Gateway)){
            $this->Gateway = $Gateway;
        } else {
            $this->Gateway = $this->Gateway . $this->APIVersion . "/";
        }
    }
    
    public function setAPIVersion($APIVersion) {
        $this->APIVersion = $APIVersion;
        $this->setGateway();
    }
    
    /* Sheet */
    
    public function Sheet($SheetID=null) {
        if (!is_null($SheetID)){
            $this->CurrentSheet = $SheetID;
            $this->getSheet($this->CurrentSheet);
        }
        return($this);
    }
    
    public function getSheet($SheetID, $Parameters=array()) {
        $this->CurrentSheet = $SheetID;
        $this->CurrentSheetObject = $this->getObject('sheets', $SheetID, $Parameters);
        return($this);
    }
    
    public function getSheetObject($SheetID, $Parameters=array()) {
        $this->getSheet($SheetID, $Parameters);
        return($this->CurrentSheetObject);
    }
    
    /* Data Tree */
    
    public function getTree() {
        $Data = [];
        foreach ($this->CurrentSheetObject->rows as $Row){
            $Data[$Row->id]['id'] = $Row->id;
            $Data[$Row->id]['data'] = $Row;
            if (!empty($Row->parentId)){
                $Data[$Row->id]['parentid'] = $Row->parentId;
            } else {
                $Data[$Row->id]['parentid'] = null;
            }
        }
        return($this->buildTree($Data));
    }
    
    public function buildTree($Data, $ParentId = null) {
        $oData = array();
        foreach($Data as $item) {
            if($item['parentid'] == $ParentId) {
                $oData[$item['id']] = [
                    'data' => $item['data'],
                    'parentid' => $item['parentid']
                ];
                $Children =  $this->buildTree($Data, $item['id']);
                if($Children) {
                    $oData[$item['id']]['children'] = $Children;
                }
            }
        }
        return $oData;
    }
    
    /* Document Generator */
    
    public function genHTMLDoc($SheetID, $HeadingCol=0, $DescriptionCol=1, $HeadingDepth=1) {
        $Tree = $this->getSheet($SheetID)->getTree();
        $this->HTMLDoc = "";
        $Snippet = $this->buildSnippet($Tree, $HeadingCol, $DescriptionCol, $HeadingDepth);
        return($Snippet);
    }
    
    public function buildSnippet($Tree, $HeadingCol=0, $DescriptionCol=1, $HeadingDepth=1) {
        $HTMLDoc = "";
        $N = 0;
        foreach ($Tree as $Item){
            $N++;
            if (array_key_exists($HeadingCol, $Item['data']->cells) && is_object($Item['data']->cells[$HeadingCol]) && !empty($Item['data']->cells[$HeadingCol]->value)){
                $HTMLDoc .= "<h$HeadingDepth>" . $Item['data']->cells[$HeadingCol]->value . "</h$HeadingDepth>";
            }
            if (array_key_exists($DescriptionCol, $Item['data']->cells) && is_object($Item['data']->cells[$DescriptionCol]) && !empty($Item['data']->cells[$DescriptionCol]->value)){
                $HTMLDoc .= "<p>" .  $Item['data']->cells[$DescriptionCol]->value . "</p>";
            }
            if (array_key_exists('children', $Item) && is_array($Item['children']) && count($Item['children']) > 0){
                $ChildSnippet = $this->buildSnippet((array)$Item['children'], $HeadingCol, $DescriptionCol, $HeadingDepth+1);
                if (!empty($ChildSnippet)){
                    $HTMLDoc .= $ChildSnippet;
                }
            }
        }
        return($HTMLDoc);
    }
    
    public function cacheString($String, $Name=null) {
        if (is_null($Name)){ $Name = time() . '.string.html'; }
        file_put_contents('cache/' . $Name, $String, FILE_APPEND);
        return($Name);
    }
    
    public function cacheGet($Name) {
        return(file_get_contents('cache/' . $Name));
    }
    
    /* Requests */
    
    public function getObject($Object, $ObjectID, $Parameters) {
        return($this->getResource($Object . "/". $ObjectID, $Parameters));
    }
    
    public function getResource($Resource, $Parameters) {
        $Headers = ["Authorization: Bearer " . $this->APIKey];
        $cSession = curl_init($this->Gateway . "$Resource?" . http_build_query($Parameters));
        error_log("Receiving resource: " . $this->Gateway . "$Resource?" . http_build_query($Parameters));
        curl_setopt($cSession, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $Headers);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        $ObjectData = curl_exec($cSession);
        return(json_decode($ObjectData));
    }
    
}
