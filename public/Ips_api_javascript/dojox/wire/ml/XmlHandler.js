//>>built
define("dojox/wire/ml/XmlHandler",["dojo","dijit","dojox","dojo/require!dojox/wire/ml/RestHandler,dojox/xml/parser,dojox/wire/_base,dojox/wire/ml/util"],function(a,g,c){a.provide("dojox.wire.ml.XmlHandler");a.require("dojox.wire.ml.RestHandler");a.require("dojox.xml.parser");a.require("dojox.wire._base");a.require("dojox.wire.ml.util");a.declare("dojox.wire.ml.XmlHandler",c.wire.ml.RestHandler,{contentType:"text/xml",handleAs:"xml",_getContent:function(e,f){var b=null;if("POST"==e||"PUT"==e){var d=
f[0];d&&(a.isString(d)?b=d:(b=d,b instanceof c.wire.ml.XmlElement?b=b.element:9===b.nodeType&&(b=b.documentElement),b='\x3c?xml version\x3d"1.0"?\x3e'+c.xml.parser.innerXML(b)))}return b},_getResult:function(a){a&&(a=new c.wire.ml.XmlElement(a));return a}})});