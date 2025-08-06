@echo "Output redirected to ..\server.log. Server starting at http://localhost:9000."
..\php.exe --server localhost:9000 --docroot documents 2> ..\server.log
