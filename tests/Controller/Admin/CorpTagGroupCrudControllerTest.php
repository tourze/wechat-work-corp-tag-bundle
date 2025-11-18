<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\DomCrawler\Crawler;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkCorpTagBundle\Controller\Admin\CorpTagGroupCrudController;
use WechatWorkCorpTagBundle\Entity\CorpTagGroup;

/**
 * 企业标签分组 CRUD 控制器测试
 *
 * @internal
 */
#[CoversClass(CorpTagGroupCrudController::class)]
#[RunTestsInSeparateProcesses]
class CorpTagGroupCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): CorpTagGroupCrudController
    {
        return new CorpTagGroupCrudController();
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '企业' => ['企业'];
        yield '应用' => ['应用'];
        yield '分组名' => ['分组名'];
        yield '远程ID' => ['远程ID'];
        yield '排序' => ['排序'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'corp' => ['corp'];
        yield 'agent' => ['agent'];
        yield 'name' => ['name'];
        yield 'sortNumber' => ['sortNumber'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'name' => ['name'];
        yield 'sortNumber' => ['sortNumber'];
    }

    public function testConfigureFields(): void
    {
        $controller = new CorpTagGroupCrudController();
        $fields = $controller->configureFields('index');

        $fieldArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldArray);
    }

    public function testConfigureCrud(): void
    {
        $controller = new CorpTagGroupCrudController();
        $crud = $controller->configureCrud(
            Crud::new()
        );

        // 验证是 Crud 类型
        self::assertSame(Crud::class, get_class($crud));
    }

    public function testConfigureActions(): void
    {
        $controller = new CorpTagGroupCrudController();
        $actions = $controller->configureActions(
            Actions::new()
        );

        // 验证Actions配置正确 - 通过调用方法验证
        $actionsDto = $actions->getAsDto(Crud::PAGE_INDEX);

        // 验证包含基本的CRUD操作
        $indexActions = $actionsDto->getActions();
        self::assertNotEmpty($indexActions, '应该包含索引页操作');

        // 验证基本操作存在性
        $hasDetailAction = false;
        foreach ($indexActions as $actionKey => $actionValue) {
            if (Action::DETAIL === $actionKey) {
                $hasDetailAction = true;
                break;
            }
        }
        self::assertTrue($hasDetailAction, '应该包含详情操作');
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问新建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 获取表单
        $form = $crawler->selectButton('Create')->form();

        // 提交空表单，触发必填字段验证
        $crawler = $client->submit($form, [
            'CorpTagGroup[name]' => '',
        ]);

        // 验证返回422状态码（表单验证失败）
        $this->assertResponseStatusCodeSame(422);

        // 验证必填字段错误消息
        $content = $crawler->html();
        $this->assertStringContainsString('should not be blank', $content);

        // 验证具体字段的错误
        $this->assertFieldValidationError($crawler, 'CorpTagGroup[name]');
    }

    private function assertFieldValidationError(Crawler $crawler, string $fieldName): void
    {
        $field = $crawler->filter("input[name=\"{$fieldName}\"]");
        if (0 === $field->count()) {
            return;
        }

        $formGroup = $field->closest('.form-group');
        if (null === $formGroup) {
            return;
        }

        $errorMessage = $formGroup->filter('.invalid-feedback, .form-error-message');
        if ($errorMessage->count() > 0) {
            $this->assertStringContainsString('should not be blank', $errorMessage->text());
        }
    }
}
