<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>API Docs</title>
    <link rel="stylesheet" type="text/css" href="/docs/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="/docs/index.css" />
    <link rel="icon" type="image/png" href="/docs/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/docs/favicon-16x16.png" sizes="16x16" />
</head>

<body>

<div id="swagger-ui"></div>
<script src="/docs/swagger-ui-bundle.js" charset="UTF-8"> </script>
<script src="/docs/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
<script type="application/javascript" charset="UTF-8">
    window.onload = function() {

        // the following lines will be replaced by docker/configurator, when it runs in a docker-container
        const ui = SwaggerUIBundle({
            url: "/api/docs/open-api",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });

        ui.initOAuth({
            clientId: "<?= $clientId ?>",
            clientSecret: "<?= $clientSecret ?>",
            appName: "Bone Framework App",
            scopeSeparator: " ",
            scopes: "basic"
        });

        window.ui = ui;
    };

</script>
</body>
</html>
