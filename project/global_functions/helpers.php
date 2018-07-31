<?php 

function input(Array $opt){
    if(!array_key_exists("name",$opt)){
        echo "Mandatory Name Option Missing!";
    }else{
        $name = "";
        $id = "";
        $for = "";
        if(array_key_exists("name",$opt)){
            $name = "name = \"{$opt["name"]}\"";
            if(!array_key_exists("id",$opt)){
                $id   = "id = \"{$opt["name"]}\"";
                $for  = "for = \"{$opt["name"]}\"";
            }else{
                $id   = "id = \"{$opt["id"]}\"";
                $for  = "for = \"{$opt["id"]}\"";
            }
        }

        $id  = removeWhiteSpaces($id);
        $for = removeWhiteSpaces($for);

        $class = "";
        if(array_key_exists("class",$opt)){
            $class = "class = \"{$opt["class"]}\"";
        }
        $class  = removeWhiteSpaces($class);

        $label = "";
        if(array_key_exists("label",$opt)){
            $label = "{$opt["label"]}";
        }
        $label  = removeWhiteSpaces($label);
    
        $extra = "";
        if(array_key_exists("extra",$opt)){
            $extra = $opt["extra"];
        }
        $extra  = removeWhiteSpaces($extra);

        $type = "";
        if(!array_key_exists("type",$opt)){
            $type = "type=\"text\"";
        }else{
            $type = "type=\"{$opt["type"]}\"";
        }
        $type  = removeWhiteSpaces($type);

        $value = "";
        if(array_key_exists("value",$opt)){
            $value = "value=\"{$opt["value"]}\"";
        }
        $value  = removeWhiteSpaces($value);

        $placeholder = "";
        if(array_key_exists("placeholder",$opt)){
            $placeholder = "placeholder=\"{$opt["placeholder"]}\"";
        }
        $placeholder  = removeWhiteSpaces($placeholder);
        
        $disable = "";
        if(array_key_exists("disable",$opt)){
            if($opt["disable"] == 'x')
                    $disable = "disabled";
        }
        $disable  = removeWhiteSpaces($disable); 
        
        $block = "<div class=\"form-group\">
        <label {$for}>{$label}</label>
        <input {$type} {$id} {$name} {$value} {$placeholder} {$disable} {$extra} class=\"form-control {$class}\"/>
        </div><!-- Field: {$name} -->";
        echo $block;
    }
}

function _a(Array $opt){
    if(!array_key_exists("text",$opt)){
        echo "Mandatory Text Option Missing!";
    }else{
        $text = "";
        $href = "";
        if(array_key_exists("text",$opt)){
            $text = "{$opt["text"]}";
            if(!array_key_exists("href",$opt)){
                $href = "href = \"#\"";
            }else{
                $href = "href = \"{$opt["href"]}\"";
            }
        }
        $href  = removeWhiteSpaces($href); 

        $name = "";
        if(array_key_exists("name",$opt)){
            $name = "name = \"{$opt["name"]}\"";
        }
        $name  = removeWhiteSpaces($name); 

        $class = "";
        if(array_key_exists("class",$opt)){
            $class = "class = \"{$opt["class"]}\"";
        }
        $class  = removeWhiteSpaces($class); 

        $id = "";
        if(array_key_exists("id",$opt)){
            $id = "id = \"{$opt["id"]}\"";
        }
        $id  = removeWhiteSpaces($id); 

        $extra = "";
        if(array_key_exists("extra",$opt)){
            $extra = "{$opt["extra"]}";
        }
        $extra  = removeWhiteSpaces($extra); 

        $type = "";
        if(array_key_exists("type",$opt)){
            $type = "{$opt["type"]}";
        }
        $type  = removeWhiteSpaces($type); 

        $block = "<a {$id} {$href} {$type} {$name} {$class} {$extra}>{$text}</a>";
        echo $block;
    }
}

$th = [];
$tb = [];
$tf = [];

function th(Array $opt){
    global $th;
    $text = "";
    if(array_key_exists("text",$opt)){
        $text = $opt["text"];
    }
    $text  = removeWhiteSpaces($text); 

    $attrib = "";
    if(array_key_exists("attrib",$opt)){
        $attrib = $opt["attrib"];
    }
    $attrib  = removeWhiteSpaces($attrib); 
    $block = "<th {$attrib}>{$text}<i class=\"icomoon-menu-open\"></i></th>";
    array_push($th, $block);
}

function tb(Array $opt){
    global $tb;
    $text = "";
    if(array_key_exists("text",$opt)){
        $text = $opt["text"];
    }
    $text  = removeWhiteSpaces($text);

    $attrib = "";
    if(array_key_exists("attrib",$opt)){
        $attrib = "data-attribute = \"{$opt["attrib"]} :\"";
    }
    $attrib  = removeWhiteSpaces($attrib); 

    $extra = "";
    if(array_key_exists("extra",$opt)){
        $extra = $opt["extra"];
    }
    $extra  = removeWhiteSpaces($extra);

    $block = "<td {$attrib} {$extra}>{$text}</td>";
    array_push($tb, $block);
}

function tf(Array $opt){
    global $tf;
    $text = "";
    if(array_key_exists("text",$opt)){
        $text = $opt["text"];
    }
    $text  = removeWhiteSpaces($text);

    $attrib = "";
    if(array_key_exists("attrib",$opt)){
        $attrib = $opt["attrib"];
    }
    $attrib  = removeWhiteSpaces($attrib); 

    $extra = "";
    if(array_key_exists("extra",$opt)){
        $extra = $opt["extra"];
    }
    $extra  = removeWhiteSpaces($extra);

    $block = "<td {$attrib} {$extra}>{$text}</td>";
    array_push($tf, $block);
}

function table($opt = []){
    global $th;
    global $tb;
    global $tf;

    $id = "";
    if(array_key_exists("id",$opt)){
        $id = "id = \"{$opt["id"]}\"";
        $id = removeWhiteSpaces($id);
    }
    $id  = removeWhiteSpaces($id);

    $class = "";
    if(array_key_exists("class",$opt)){
        $class = $opt["class"];
        $class = removeWhiteSpaces($class);
    }
    $class  = removeWhiteSpaces($class);

    $attrib = "";
    if(array_key_exists("attrib",$opt)){
        $attrib = $opt["attrib"];
        $attrib = removeWhiteSpaces($opt["attrib"]);
    }
    $attrib  = removeWhiteSpaces($attrib);
    
    $block = "<div class=\"tables-widget tables-widget-default tables-widget-blue tables-widget-striped\">
                <div class=\"tables-widget-header\"></div>
                    <table width=\"100%\" {$attrib} {$id} class=\"table table-striped table-bordered {$class}\">
                        <thead>
                            <tr>";
                                if(!empty($th)){
                                    foreach($th as $header_row){
                                        $block .= $header_row;
                                    }
                                }
                $block .= "</tr>
                            </thead>
                            <tbody>
                                <tr>";
                                    if(!empty($tb)){
                                        foreach($tb as $body_row){
                                            $block .= $body_row;
                                        } 
                                    }
                $block .= "</tr>
                            </tbody>
                            <tfoot>
                                <tr>";
                                    if(!empty($tf)){
                                        foreach($tf as $footer_row){
                                            $block .= $footer_row;
                                        } 
                                    }
                    $block .= "</tr>
                            </tfoot>
                        </table>
                    </div>
                </div>";

    echo $block;
}

