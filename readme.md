edouard@edouard-omen:~/PhpstormProjects/quete_symfony/wild-series$ php bin/console make:migration

           
  Success! 
           

 Next: Review the new migration "src/Migrations/Version20191125160723.php"
 Then: Run the migration with php bin/console doctrine:migrations:migrate
 See https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
edouard@edouard-omen:~/PhpstormProjects/quete_symfony/wild-series$ php bin/console doctrine:migrations:migrate
                                                              
                    Application Migrations                    
                                                              

WARNING! You are about to execute a database migration that could result in schema changes and data loss. Are you sure you wish to continue? (y/n)
Migrating up to 20191125160723 from 20191125154621

  ++ migrating 20191125160723

     -> CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, poster VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB

  ++ migrated (took 709.4ms, used 16M memory)

  ------------------------

  ++ finished in 714.6ms
  ++ used 16M memory
  ++ 1 migrations executed
  ++ 1 sql queries
edouard@edouard-omen:~/PhpstormProjects/quete_symfony/wild-series$ php bin/console doctrine:schema:validate

Mapping
-------

                                                                                                                        
 [OK] The mapping files are correct.                                                                                    
                                                                                                                        

Database
--------

                                                                                                                        
 [OK] The database schema is in sync with the mapping files.                                                            
                                                                                                                        

edouard@edouard-omen:~/PhpstormProjects/quete_symfony/wild-series$ php bin/console doctrine:mapping:info

 Found 2 mapped entities:

 [OK]   App\Entity\Category
 [OK]   App\Entity\Program
