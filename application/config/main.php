<?php

/**
 * Database Configuration
 * \System\Database::connect(identifier, driver, server, username, password, database);
 */
\System\Database::connect_dsn('main', 'sqlite:ixt_demo.sqlite');