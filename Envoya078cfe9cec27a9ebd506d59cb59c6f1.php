<?php $currentBranch = isset($currentBranch) ? $currentBranch : null; ?>
<?php $branch = isset($branch) ? $branch : null; ?>
<?php $projectRoot = isset($projectRoot) ? $projectRoot : null; ?>
<?php $env = isset($env) ? $env : null; ?>
<?php $environment = isset($environment) ? $environment : null; ?>
<?php $now = isset($now) ? $now : null; ?>
<?php $__container->servers(['personal' => ['root@vrkansagara.in']]); ?>

<?php
    $now = new DateTime();

    $environment = isset($env) ? $env : "testing";
    $projectRoot= '/var/www/getlaminas.in';

    <?php if ($branch): ?>
        $currentBranch =  $branch
    <?php else: ?>if
        $currentBranch =  'master'
    <?php endif; ?>
?>

<?php $__container->startTask('deploy', ['on' => 'personal']); ?>
    ls -lA <?php echo $projectRoot; ?>


git checkout .
git reset --hard HEAD
git clean -fd

git pull origin <?php echo $branch; ?>


<?php $__container->endTask(); ?>
