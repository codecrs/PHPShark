<?php
namespace core{
    /****************SET UP YOUR ROUTERS HERE  */
    //Public Routers
    Router::route("/",["path" => "public"]);
    Router::route("404",["path" => "public".DS."error"]);
  /****************SET UP YOUR ROUTERS HERE  */
}
