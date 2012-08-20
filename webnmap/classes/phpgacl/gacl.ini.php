;<? if (; //Cause parse error to hide from prying eyes?> 
;
; *WARNING* 
;
; DO NOT PUT THIS FILE IN YOUR WEBROOT DIRECTORY. 
;
; *WARNING*
;
; Anyone can view your database password if you do!
;
debug 			= FALSE

;
;Database
;
db_type 		= "mysql"
db_host			= "213.171.218.244"
db_user			= "w3securityadm"
db_password		= "NRe8bMmu"
db_name			= "w3security"
db_table_prefix		= "auth_"

;
;Caching
;
caching			= FALSE
force_cache_expire	= TRUE
cache_dir		= "/tmp"
cache_expire_time	= 600

;
;Admin interface
;
items_per_page 		= 100
max_select_box_items 	= 100
max_search_return_items = 200

;NO Trailing slashes
smarty_dir 		= "smarty/libs"
smarty_template_dir 	= "templates"
smarty_compile_dir 	= "smarty/templates_c"

