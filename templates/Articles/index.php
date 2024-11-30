<!-- File: templates/Articles/index.php -->

<h1>記事一覧</h1>
<table>
    <tr>
        <th>タイトル</th>
        <th>作成日時</th>
        <th>操作</th>
    </tr>
    <!-- ここで、$articlesクエリーオブジェクト繰り返し、記事情報を出力 -->

    <?php foreach ($articles as $article): ?>
        <tr>
            <td>
                <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
            </td>
            <td>
                <?= $article->created->format(DATE_RFC850) ?>
            </td>
            <td>
                <?= $this->Html->link('編集',['action'=>'edit', $article->slug]) ?>
                <?= $this->Form->postLink(
                    '削除',
                    ['action' => 'delete', $article->slug],
                    ['confirm'=>'よろしいですか？']
                )
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>