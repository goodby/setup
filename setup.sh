#!/usr/bin/env bash

vendorname="__vendor-name__"
vendor_name="__vendor_name__"
VENDOR_NAME="__VENDOR_NAME__"
VendorName="__VendorName__"

packagename="__package-name__"
package_name="__package_name__"
PACKAGE_NAME="__PACKAGE_NAME__"
PackageName="__PackageName__"

set -eux

curl -s https://getcomposer.org/installer | php
php composer.phar create-project goodby/setup "$vendorname/$packagename"
rm -f composer.phar 
cd "$vendorname/$packagename"

# replace vendor name
find . -type f -print0 | xargs -0 perl -i -pe 's/vendor-name/$vendorname/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/vendor_name/$vendor_name/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/VENDOR_NAME/$VENDOR_NAME/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/VendorName/$VendorName/g'

# replace package name
find . -type f -print0 | xargs -0 perl -i -pe 's/package-name/$packagename/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/package_name/$package_name/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/PACKAGE_NAME/$PACKAGE_NAME/g'
find . -type f -print0 | xargs -0 perl -i -pe 's/PackageName/$PackageName/g'

# replace "goodby/setup"
find . -type f -print0 | xargs -0 perl -i -pe 's/goodby\/setup/$vendorname\/$packagename/g'

set -eu

echo "Good bye, enjoy it!"
