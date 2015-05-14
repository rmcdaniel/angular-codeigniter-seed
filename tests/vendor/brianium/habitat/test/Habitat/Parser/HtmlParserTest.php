<?php
namespace Habitat\Parser;

class HtmlParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;

    /**
     * Generated list of ENV vars included from phpinfo()
     * and the build in PHP 5.4 web server
     *
     * @var array
     */
    protected $expected = array(
        "SSH_AGENT_PID" => "1531",
        "GPG_AGENT_INFO" => "/tmp/keyring-ZxnEkM/gpg:0:1",
        "TERM" => "xterm",
        "SHELL" => "/bin/bash",
        "XDG_SESSION_COOKIE" => "cbddedf88010e6b91aa8e2b200000007-1369407566.555787-40863076",
        "WINDOWID" => "73400325",
        "GNOME_KEYRING_CONTROL" => "/tmp/keyring-ZxnEkM",
        "USER" => "developer",
        "LS_COLORS" => "rs=0:di=01;34:ln=01;36:mh=00:pi=40;33:so=01;35:do=01;35:bd=40;33;01:cd=40;33;01:or=40;31;01:su=37;41:sg=30;43:ca=30;41:tw=30;42:ow=34;42:st=37;44:ex=01;32:*.tar=01;31:*.tgz=01;31:*.arj=01;31:*.taz=01;31:*.lzh=01;31:*.lzma=01;31:*.tlz=01;31:*.txz=01;31:*.zip=01;31:*.z=01;31:*.Z=01;31:*.dz=01;31:*.gz=01;31:*.lz=01;31:*.xz=01;31:*.bz2=01;31:*.bz=01;31:*.tbz=01;31:*.tbz2=01;31:*.tz=01;31:*.deb=01;31:*.rpm=01;31:*.jar=01;31:*.war=01;31:*.ear=01;31:*.sar=01;31:*.rar=01;31:*.ace=01;31:*.zoo=01;31:*.cpio=01;31:*.7z=01;31:*.rz=01;31:*.jpg=01;35:*.jpeg=01;35:*.gif=01;35:*.bmp=01;35:*.pbm=01;35:*.pgm=01;35:*.ppm=01;35:*.tga=01;35:*.xbm=01;35:*.xpm=01;35:*.tif=01;35:*.tiff=01;35:*.png=01;35:*.svg=01;35:*.svgz=01;35:*.mng=01;35:*.pcx=01;35:*.mov=01;35:*.mpg=01;35:*.mpeg=01;35:*.m2v=01;35:*.mkv=01;35:*.webm=01;35:*.ogm=01;35:*.mp4=01;35:*.m4v=01;35:*.mp4v=01;35:*.vob=01;35:*.qt=01;35:*.nuv=01;35:*.wmv=01;35:*.asf=01;35:*.rm=01;35:*.rmvb=01;35:*.flc=01;35:*.avi=01;35:*.fli=01;35:*.flv=01;35:*.gl=01;35:*.dl=01;35:*.xcf=01;35:*.xwd=01;35:*.yuv=01;35:*.cgm=01;35:*.emf=01;35:*.axv=01;35:*.anx=01;35:*.ogv=01;35:*.ogx=01;35:*.aac=00;36:*.au=00;36:*.flac=00;36:*.mid=00;36:*.midi=00;36:*.mka=00;36:*.mp3=00;36:*.mpc=00;36:*.ogg=00;36:*.ra=00;36:*.wav=00;36:*.axa=00;36:*.oga=00;36:*.spx=00;36:*.xspf=00;36:",
        "XDG_SESSION_PATH" => "/org/freedesktop/DisplayManager/Session0",
        "XDG_SEAT_PATH" => "/org/freedesktop/DisplayManager/Seat0",
        "SSH_AUTH_SOCK" => "/tmp/keyring-ZxnEkM/ssh",
        "SESSION_MANAGER" => "local/ost-devbox:@/tmp/.ICE-unix/1460,unix/ost-devbox:/tmp/.ICE-unix/1460",
        "DEFAULTS_PATH" => "/usr/share/gconf/ubuntu-2d.default.path",
        "XDG_CONFIG_DIRS" => "/etc/xdg/xdg-ubuntu-2d:/etc/xdg",
        "PATH" => "/home/developer/bin:/usr/lib/lightdm/lightdm:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games",
        "DESKTOP_SESSION" => "ubuntu-2d",
        "PWD" => "/home/developer/projects/habitat",
        "GNOME_KEYRING_PID" => "1449",
        "LANG" => "en_US.UTF-8",
        "MANDATORY_PATH" => "/usr/share/gconf/ubuntu-2d.mandatory.path",
        "NODE_PATH" => "/usr/lib/nodejs:/usr/lib/node_modules:/usr/share/javascript",
        "UBUNTU_MENUPROXY" => "libappmenu.so",
        "GDMSESSION" => "ubuntu-2d",
        "SHLVL" => "1",
        "HOME" => "/home/developer",
        "GNOME_DESKTOP_SESSION_ID" => "this-is-deprecated",
        "LOGNAME" => "developer",
        "XDG_DATA_DIRS" => "/usr/share/ubuntu-2d:/usr/share/gnome:/usr/local/share/:/usr/share/",
        "DBUS_SESSION_BUS_ADDRESS" => "unix:abstract=/tmp/dbus-jVpxjJZIy5,guid=22c332b058eaa4dfbc2be5340000000e",
        "LESSOPEN" => "| /usr/bin/lesspipe %s",
        "DISPLAY" => ":0",
        "XDG_CURRENT_DESKTOP" => "Unity",
        "LESSCLOSE" => "/usr/bin/lesspipe %s %s",
        "COLORTERM" => "gnome-terminal",
        "XAUTHORITY" => "/home/developer/.Xauthority",
        "OLDPWD" => "/home/developer/projects/habitat",
        "_" => "/usr/bin/php"
    );

    public function setUp()
    {
        $this->parser = new HtmlParser();
    }

    public function test_parse_returns_array_with_proper_key_value_pairs()
    {
        $input = file_get_contents(FIXTURES . DS . 'output' . DS . 'phpinfo.html');
        $vars = $this->parser->parse($input);
        $this->assertEquals($this->expected, $vars);
    }
}