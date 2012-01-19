SPARK_VERSION=$(shell php spark version)

all: build

build:
	zip -r spark-manager-$(SPARK_VERSION).zip lib test spark README.md
