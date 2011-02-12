# CodeIgniter Spark

Spark is a way to pull down packages automatically

    $ tools/spark install -v1.1 gravatar_helper

And then you can load the package like so:

    $this->add_package_path('sparks/gravatar_helper/1.1');
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

## Conventions

There needs to be some sort of namespacing in place to avoid conflicts with standard
application code. Perhaps perfixing everything with spark.?

    $this->load->add_package_path('sparks/wutang');
   
    $this->load->helper('spark.wutang'); # spark_wutang_helper.php, gross
    $this->load->library('spark.wutang.name');

Actually, by forcing a prefix with spark., we'd be able to load files much
faster. Absolutely no guessing, which saves a lot of disk I/O.

But then what would the classname be? $this->spark_wutang_lib? Ack!

## Bootstrap

Some sparks might involve the db. Do we provide setUp and tearDown? Bootstrap for ordinary loads?
