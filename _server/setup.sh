set -eux

vendorname="__vendor-name__"
vendor_name="__vendor_name__"
VENDOR_NAME="__VENDOR_NAME__"
VendorName="__VendorName__"

packagename="__package-name__"
package_name="__package_name__"
PACKAGE_NAME="__PACKAGE_NAME__"
PackageName="__PackageName__"

# clone goodby/setup
git clone git://github.com/goodby/setup.git "$vendorname/$packagename"
cd "$vendorname/$packagename"

# replace vendor name
find . -type f -print0 | xargs -0 perl -i -pe "s/vendor-name/$vendorname/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/vendor_name/$vendor_name/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/VENDOR_NAME/$VENDOR_NAME/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/VendorName/$VendorName/g"

# replace package name
find . -type f -print0 | xargs -0 perl -i -pe "s/package-name/$packagename/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/package_name/$package_name/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/PACKAGE_NAME/$PACKAGE_NAME/g"
find . -type f -print0 | xargs -0 perl -i -pe "s/PackageName/$PackageName/g"

# replace "goodby/setup"
find . -type f -print0 | xargs -0 perl -i -pe "s/goodby\/setup/$vendorname\/$packagename/g"

# move directory
mv "src/VendorName/PackageName" "src/VendorName/$PackageName"
mv "src/VendorName" "src/$VendorName"

# delete set up tools
rm -rf _*

# delete "goodby/setup" .git directory
rm -rf .git

# perform project's first commit
git init
git add README.md
git commit -m "first commit"

# perform project's second commit
git add -A
git commit -m "set up project"

# add remote origin
git remote add origin "git@github.com:$vendorname/$packagename.git"

# Install composer if not exists
if type "composer.phar" > /dev/null 2>&1
then
    COMPOSER="composer.phar"
else
    curl -s https://getcomposer.org/installer | php
    COMPOSER="php composer.phar"
fi

$COMPOSER install --dev

set +x

echo ""
echo "  Setup is almost done."
echo "  "
echo "  next step:"
echo "    Move to project directory:"
echo "    \$ cd $vendorname/$packagename"
echo ""
echo "    If you won't to push to GitHub:"
echo "    \$ git push -u origin master"