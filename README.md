# CodeIgniter Spark

Spark is a way to pull down packages automatically

    $ tools/spark install -v1.0 gravatay

And then you can load the package like so:

    $this->add_package_path('./third_party/gravatay/1.1');
    $this->load->helper('gravatar');

---

## Adding a package

    $ tools/spark install -v1.0 gravatay
    $ tools/spark install gravatay # most recent version

## Removing a package

    $ tools/spark remove -v1.0 gravatay  # remove a specific version
    $ tools/spark remove gravatay -f  # remove all

## List installed

    $ tools/spark list
