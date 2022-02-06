<h1>xVault: Credential management web client.</h1>
    <p> 🔓 A study project consisting of a Laravel API & a VueJS frontend. The application is a centralized repository of online website credentials for those who diversify its account access information. </p>
    <h2>How to test it?</h2>
    <h3> 🔷 This project is published at Heroku: https://xvault-app.herokuapp.com/ </h3>
    <h3> 🔷 You can also run it locally: </h3>
    <ol>
        <li>In a terminal, run command: "git clone https://github.com/ricardodidimo/xVault.git"</li>
        <li>Run command: "cd ./xVault" <strong>| Moving to clone folder</strong></li>
        <li>Run command: "composer install"  <strong>| installing laravel dependencies</strong></li>
        <li>Run command: "npm instal" <strong>| installing vuejs dependencies</strong></li>
        <li>Run command: "npm run dev" <strong>| building public/vue assets</strong></li>
        <li>Create on root of the repo folder a .env file, copy and move skeleton from .env.example to new file</li>
        <li>Run command: "php artisan key:generate"</li>
        <li>Manually insert your local database credentials inside vars prefixed with 'DB'</li>
        <li>Run command: "php artisan migrate"</li>
        <li>Manually insert mailing host credentials on .env</li>
        <li>Run command: "php artisan serve" <strong>| now test on your browser</strong></li>
    </ol>
    <h4>Running tests</h4>
    <ol>
        <li>Fill up the .env vars referencing 'DB_TESTING_*' with database credentials</li>
        <li>Before running the test suit you must manually create an empty folder on 'tests' directory named 'Unit' to avoid a PHPUnit error</li>
        <li>Inside repo folder run command: "php artisan test" <strong>| This will run ALL tests</strong></li>
    </ol>
    <small>use "php artisan test --filter &lt;TestClassName&gt;" to run a specific set of tests defined within a class <strong>OR</strong> "php artisan test --filter &lt;TestClassName&gt;::&lt;TestMethodName&gt;" to run one single test.</small>
    <h2>Tecnologies and techniques</h2>
    <h3> 🔺 This made use of PHP 8 & Laravel 8 along with its conventions and ecosystem</h3>
    <ul>
    <li>Database using migrations system; interactions made with Eloquent ORM. </li>
    <li>SPA authentication uses Sanctum, involves confirmation mailing and laravel queues for such.</li>
    <li>A TDD approach was taken for the API development with help from PHPUnit testing framework.</li>
    <li>PHPCS linter took action to ensure PSR12 code style compliance.</li>
    </ul>
    <h3> 🟢 This made use of Javascript ES6+ & VueJS 2 along with its conventions and ecosystem</h3>
    <ul>
    <li>Uses Vuex and stores to handle application state.</li>
    <li>Uses Axios to make AJAX requests to API.</li>
    <li>Uses Bootstrap CSS framework for interface design.</li>
    </ul>
    
    
