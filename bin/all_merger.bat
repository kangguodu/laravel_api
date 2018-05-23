@echo on
@echo All swagger apidoc merger
swagger-merger -i ../yamls/main_local.yaml -o ../yamls/swagger_local.yaml && swagger-merger -i ../yamls/main.yaml -o ../yamls/swagger.yaml

