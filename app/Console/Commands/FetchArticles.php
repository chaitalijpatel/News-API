<?php

namespace App\Console\Commands;

use App\Models\Article;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchArticles extends Command
{
    protected $signature = 'articles:fetch';

    public function handle()
    {
        $client = new Client([
            'verify' => storage_path('cacert.pem'),
        ]);

        // NewsAPI
        $this->fetchNewsAPIArticles($client);

        // Guardian
        $this->fetchGuardianArticles($client);

        // New York Times
        $this->fetchNYTArticles($client);
    }

    protected function fetchNewsAPIArticles($client)
    {
        try {
            $newsURL = env('NewsAPI_URL').'?country=us&apiKey='.env('NewsAPI_KEY');
            $newsApiResponse = $client->get($newsURL);
            $articles = json_decode($newsApiResponse->getBody()->getContents())->articles;

            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['title' => $article->title],
                    [
                        'author' => $article->author ?? null,
                        'source' => $article->source->name,
                        'category' => 'general',
                        'content' => $article->content ?? null,
                        'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->publishedAt),
                    ]
                );
            }

            $this->info("NewsAPI fetched successfully!");

        } catch (\Exception $e) {
            $this->error("Failed: " . $e->getMessage());
        }
    }

    protected function fetchGuardianArticles($client)
    {
        try {

            $guardianURL = env('GUARDIAN_URL').'?search?page=2&q=debate&api-key='.env('GUARDIAN_KEY');
            $guardianApiResponse = $client->get($guardianURL);
            $articles = json_decode($guardianApiResponse->getBody()->getContents())->response->results;

            foreach ($articles as $article) {
              
                $content = isset($article->fields->body) ? $article->fields->body : null;

                Article::updateOrCreate(
                    ['title' => $article->webTitle],
                    [
                        'author' => $article->author ?? null, 
                        'source' => 'The Guardian',
                        'category' => $article->pillarName ?? null, 
                        'content' => $content, 
                        'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->webPublicationDate),
                    ]
                );
            }

            $this->info("The Guardian fetched successfully!");

        } catch (\Exception $e) {
            $this->error("Failed: " . $e->getMessage());
        }
    }

    protected function fetchNYTArticles($client)
    {
        try {
            
            $NYTURL = env('NYT_URL').'?api-key={'.env('NYT_KEY').'}';
            $nytApiResponse = $client->get($NYTURL);
            $articles = json_decode($nytApiResponse->getBody()->getContents())->results;

            foreach ($articles as $article) {
                
                Article::updateOrCreate(
                    ['title' => $article->title],
                    [
                        'author' => implode(', ', array_column($article->byline->item ?? [], 'original')) ?: null,
                        'source' => 'The New York Times',
                        'category' => $article->section ?? null,
                        'content' => $article->abstract ?? null,
                        'published_at' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $article->published_date),
                    ]
                );
            }

            $this->info("The New York Times fetched successfully!");
        } catch (\Exception $e) {
            $this->error("Failed: " . $e->getMessage());
        }
    }
}