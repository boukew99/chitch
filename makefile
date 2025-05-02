# Chitch © its Maintainers 2025, Licensed under the EUPL

## Main targets:

PORT ?= 9001
# https://www.php.net/manual/en/features.commandline.webserver.php
build/php.log: bin/php  source source/public source/php.ini
	(command -v xdg-open >/dev/null && xdg-open "http://localhost:$(PORT)/")
	@echo "\nOpen Development Server at (http://localhost:$(PORT)) started"
	@echo "**Stop server with 'Ctrl + C'**\n"
	PHP_CLI_SERVER_WORKERS=4 $< \
	--php-ini source/php.ini \
	--server localhost:$(PORT) \
	--docroot source/public 2> $@

# sudo chown -R $USER:www-data .
# sudo apt install ./chitch.deb # install
build/chitch.deb : root
	DPKG_COMPRESSION=xz dpkg-deb --build $< $@

# For Shared-host
build/chitch.zip: brotlid
	(zip --recurse-paths --quiet "$@" $<)
	du -h $@
	rm -rf $<

build/chitch.tar.zst: chitch license.txt bin
	tar --dereference -caf  $@ $^
	rm $<

## Supplementary targets:

# needs php.log server running already
spider.log: php.log
	wget --spider --recursive -e robots=off --no-parent --level=5 --no-verbose --output-file=wget-check.log http://localhost:$(PORT)/index.html

php-syntax-check: bin/php source
	! find source -type f -name '*.php' -exec $< --syntax-check {} \; | grep -v "No syntax errors detected"

brotlid: source bin/brotli
	cp -rL $< $@
	find $@ -type f \( -name "*.html" -o -name "*.js" -o -name "*.css" -o -name "*.svg" \) -exec sh -c 'bin/brotli -c "{}" > "{}.br"' \;

# global bins
bin/% : /usr/bin/%
	ln -sfn $< $@

# Dynamic Fallback
bin/%:
	ln -s $(shell command -v $*) $@

# shasum -a 256 -c "%.sha256" # check
build/%.sha256: %
	shasum -a 256 $< > $@

# Block size of average file size
build/chitch.sqsh: chitch/
	mksquashfs $< $@ -comp zstd -b 256K

build/stash.patch: build/delta.patch
	cp $< $@
	cd source && patch --reverse -p1 < ../../../../build/stash.patch

build/commit.patch: source
	-git diff --full-index --no-prefix --histogram --find-renames=2% $</ > $@
	chmod 444 $@

# '4b825dc642cb6eb9a060e54bf8d69288fbee4904' is the empty tree object
build/pristine.patch: source
	mkdir empty
	-git diff --full-index --no-prefix --no-index empty/ $</ > $@
	chmod 444 $@
	rm -r empty

build/delta.patch: pristine source
	-git diff --full-index --no-prefix --histogram --find-renames=2% --no-index $^/ > $@ # / since symlink
	# chmod 444 $@
	rm -rf $<

build/dependencies.svg: makefile
	make chitch.deb chitch.tar.zst chitch.zip php.log -Bnd | make2graph | dot -Tsvg -o $@

# Create pristine if it doesn't exist and keep it static
pristine: build/pristine.patch
	mkdir -p $@
	cd $@ && patch -p1  < "../$<"
	find $@ -type f -exec chmod a-w {} +

source : root/srv/chitch/source/
	ln -sfn $<

chitch: root/srv/chitch/
	ln -sfn $<

# Benchmark with `time` in front of a make command
# Manual (https://www.gnu.org/software/make/manual/html_node/index.html)
