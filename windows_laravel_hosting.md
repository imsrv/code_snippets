https://stackoverflow.com/questions/45464267/setup-laravel-5-4-with-iis-10/45464268#45464268

# This is how I did it; I'm not sure it is the right way.

Install URL Rewrite module (https://www.iis.net/downloads/microsoft/url-rewrite)
Put repo in some folder E:/sites/helloworld
In IIS add a virtual directory called "helloworld" pointing to E:/sites/helloworld/public
Make sure folders bootstrap/cache and storage is open for writing.
Dont forget to make env file.

In your web.php route file put:

Route::get('/', function () {
    return view('welcome');
});

Route::get('/also', function () {
    return view('welcome');
});
Add the file web.config in public dir and paste the rules:
#################################################

<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
        <rules>
            <rule name="Rule 1" stopProcessing="true">
            <match url="^(.*)/$" ignoreCase="false" />
            <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
            </rule>
            <rule name="Rule 2" stopProcessing="true">
            <match url="^" ignoreCase="false" />
            <conditions>
                <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
            </conditions>
            <action type="Rewrite" url="index.php" />
            </rule>
        </rules>
        </rewrite>
    </system.webServer>
</configuration>
###################################################

# Add web.config file to your azure directory. Add the code below into it.

<configuration>
<system.webServer>
    <rewrite>
      <rules>
        <rule name="Main Rule" stopProcessing="true">
                <match url=".*" />
                <conditions logicalGrouping="MatchAll">
                    <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                    <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                </conditions>
                <action type="Rewrite" url="/" />
            </rule>
        </rules>
    </rewrite>
</system.webServer>
</configuration>
