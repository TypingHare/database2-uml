# NOTE: This does not work on Windows or WSL2.
#
# Move all the files and directories under `src` to the main document root of
# httpd. This recipe may fail if any warnings or errors are included in the
# output of the following command:
#
#     $ httpd -S
#
# Please execute the following command to verify that there are no warnings or
# errors:
#
#     $ httpd -S | grep "Main DocumentRoot"
#
# You should see something like this:
#
#     Main DocumentRoot: "/opt/homebrew/var/www"
#
deploy:
	@document_root=$$(httpd -S 2>/dev/null | awk -F'"' '/Main DocumentRoot:/ {print $$2}'); \
	if [ -z "$$document_root" ]; then \
	    echo "Error: Could not determine Main DocumentRoot."; exit 1; \
	fi; \
	echo "Deploying files to: $$document_root"; \
	rm -rf "$$document_root"/* && cp -r src/. "$$document_root"/

# Import materials/db2_representation.sql
import_sql:
	mysql -u root -p < materials/db2_presentation.sql