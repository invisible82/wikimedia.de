<?php

declare( strict_types = 1 );

namespace App\DataAccess;

use FileFetcher\FileFetcher;
use FileFetcher\FileFetchingException;

class WordpressApiNewsRepository implements NewsRepository {

	private $fileFetcher;

	public function __construct( FileFetcher $fileFetcher ) {
		$this->fileFetcher = $fileFetcher;
	}

	public function getLatestNewsItems(): array {
		// DE https://blog.wikimedia.de/wp-json/wp/v2/posts?tags=243
		// EN https://blog.wikimedia.de/wp-json/wp/v2/posts?tags=464

		try {
			return array_map(
				function( array $post ): NewsItem {
					return NewsItem::newInstance()
						->withTitle( $post['title']['rendered'] )
						->withLink( $post['link'] )
						->withExcerpt( $post['excerpt']['rendered'] );
				},
				$this->getPostsArray()
			);
		}
		catch ( FileFetchingException $ex ) {
			return [];
		}
	}

	private function getPostsArray(): array {
		return json_decode(
			$this->fileFetcher->fetchFile( 'TODO' ),
			true
		);
	}

}