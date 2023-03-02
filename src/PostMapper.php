<?php

namespace Blog;

use PDO;

class PostMapper
{
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getByUrlKey( $urkKey ) : ?array
    {
        $query = "SELECT * FROM post WHERE url_key = '$urkKey'";
        $statement = $this->connection->prepare( $query );
        $statement->execute();

        return array_shift( $statement->fetchAll() );
    }
}