{ lib, ... }:
{
    languages.php = {
    enable = lib.mkDefault true;
    version = lib.mkDefault "8.2";
    extensions = [ "pcov" ];

    ini = ''
      memory_limit = 2G
      realpath_cache_ttl = 3600
      session.gc_probability = 0
      display_errors = On
      error_reporting = E_ALL
      assert.active = 0
      zend.assertions = 0
      short_open_tag = 0
      zend.detect_unicode = 0
      realpath_cache_ttl = 3600
    '';
  };
}