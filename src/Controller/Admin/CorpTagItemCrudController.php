<?php

declare(strict_types=1);

namespace WechatWorkCorpTagBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatWorkCorpTagBundle\Entity\CorpTagItem;

/**
 * @extends AbstractCrudController<CorpTagItem>
 */
#[AdminCrud(routePath: '/wechat-work/corp-tag-item', routeName: 'wechat_work_corp_tag_item')]
final class CorpTagItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CorpTagItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('企业标签项目')
            ->setEntityLabelInPlural('企业标签项目')
            ->setSearchFields(['name', 'remoteId'])
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('remoteId')
            ->add('corp')
            ->add('agent')
            ->add('tagGroup')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
        ;

        yield AssociationField::new('corp', '企业')
            ->setRequired(true)
            ->hideOnIndex()
        ;

        yield AssociationField::new('agent', '应用')
            ->setRequired(true)
            ->hideOnIndex()
        ;

        yield AssociationField::new('tagGroup', '标签分组')
            ->setRequired(true)
        ;

        yield TextField::new('name', '标签名')
            ->setRequired(true)
            ->setHelp('标签的名称，最多120个字符')
        ;

        yield TextField::new('remoteId', '远程ID')
            ->hideOnForm()
            ->setHelp('企业微信系统中的标签ID')
        ;

        yield IntegerField::new('sortNumber', '排序')
            ->setHelp('数字越小排序越靠前，默认为0')
        ;

        if (Crud::PAGE_DETAIL === $pageName) {
            yield DateTimeField::new('createTime', '创建时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss')
            ;

            yield DateTimeField::new('updateTime', '更新时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss')
            ;

            yield TextField::new('createdFromIp', '创建IP');
            yield TextField::new('updatedFromIp', '更新IP');
        }
    }
}
