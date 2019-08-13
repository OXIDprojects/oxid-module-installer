export function config() {
    return {
        apiUrl: (location.href.indexOf('oxid.phar.php') !== -1 ? '/oxid.phar.php' : '') +  `/oxid/moduleinstaller/`
    }
}