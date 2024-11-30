<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
    # コントローラの初期化
    public function initialize(): void
    {
        parent::initialize();
    }
    public function index(){
        $articles = $this->paginate($this->Articles->find());
        // viewに変数を渡す
        $this->set(compact('articles'));
    }

    public function view($slug)
    {
        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        $this->set(compact('article'));
    }

    # 新しい記事を追加するためのアクション
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();
        if ($this->request->is('post')){
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // user_idの決め打ちは一時的なもので、あとで認証を構築する際に削除される
            $article->user_id = 1;
            if ($this->Articles->save($article)){
                $this->Flash->success(__('記事を追加しました'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('記事の追加に失敗しました'));
        }
        //タグのリストを取得
        $tags = $this->Articles->Tags->find('list')->all();
        // ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }

    # 記事を編集するためのアクション
    public function edit($slug)
    {
        $article = $this->Article->findBySlug($slug)->furstOrFail();
        if ($this->request->is(['post','put'])){
            $this->Article->patchEntity($article, $this->request->getData());
            if ($this->Article->save($article)){
                $this->Flash->success(__('記事を編集しました'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('記事の編集に失敗しました'));
        }
        $tags = $this->Articles->Tags->find('list')->all();

        //ビューコンテキストにtagsをセット
        $this->set('tags', $tags);

        # 別のURLへ移動してください
        $this->set('article', $article);
    }

    # 記事を削除する方法
    public function delete($slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findBySlug($slug)->firstOrFail();
        if ($this->Articles->delete($article)){
            $this->Flash->success(__('The {0} article has been deleted.', $article->title));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function tags()
    {
        $tags = $this->request->request->getParam('pass');

        $articles = $this->Articles->find('tagged', [
            'tags' => $tags
        ])
        ->all();

        //変数をビューテンプレートのコンテキストに渡す
        $this->set([
            'articles' => $articles,
            'tags' => $tags
        ]);
    }
}