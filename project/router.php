<?php
namespace core{
    /****************SET UP YOUR ROUTERS HERE  */
    //Public Routers
    Router::route("/",["path" => "public"]);
    Router::route("404",["path" => "public".DS."error"]);
    Router::route("denied",["path" => "public".DS."denied"]);
    Router::route("logout",["path" => "public".DS."logout"]);
    Router::route("callback",["path" => "public".DS."callback"]);
    Router::route("test",["path" => "public".DS."test"]);
	  Router::route("app",["path" => "public".DS."app"]);

  //Member Routers
    Router::route("member",["path" => "customer".DS."members"]);
  /****************SET UP YOUR ROUTERS HERE  */
}
