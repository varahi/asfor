#!/bin/bash
# (c) by Fedir RYKHTIK (fedir@stratis.fr), Emmanuel Vodor (emmanuelvodor@stratis.fr)
# Please configure MySQL credentials in the ~/.my.cnf file
# Usage: mysqldump.sh [database] [file]
if [ -z "$1" -o -z "$2" ]; then
    echo "Usage: mysqldump.sh [database] [file]";
    exit
fi
# Tables content to exclude
EXCLUDES=('cache_pages' 'cache_extensions' 'cache_hash' 'link_cache' 'cache_typo3temp_log' 'cache_imagesizes' 'cache_md5params' 'cache_pages' 'cache_pagesection' 'cache_treelist' 'cachingframework_cache_hash'
'cachingframework_cache_hash_tags' 'cachingframework_cache_pages' 'cachingframework_cache_pages_tags' 'cachingframework_cache_pagesection' 'cachingframework_cache_pagesection_tags'
'be_sessions' 'fe_session_data' 'fe_sessions' 'index_fulltext' 'index_grlist' 'index_phash' 'index_rel' 'index_section' 'index_stat_search' 'index_stat_word' 'index_words' 'index_debug'
'cf_cache_hash' 'cf_cache_hash_tags' 'cf_cache_news_category' 'cf_cache_news_category_tags' 'cf_cache_pages' 'cf_cache_pagesection' 'cf_cache_pagesection_tags' 'cf_cache_pages_tags' 'cf_cache_rootline'
'cf_cache_rootline_tags' 'cf_extbase_datamapfactory_datamap' 'cf_extbase_datamapfactory_datamap_tags' 'cf_extbase_object' 'cf_extbase_object_tags' 'cf_extbase_reflection' 'cf_extbase_reflection_tags'
'cf_extbase_typo3dbbackend_queries' 'cf_extbase_typo3dbbackend_queries_tags' 'cf_extbase_typo3dbbackend_tablecolumns' 'cf_extbase_typo3dbbackend_tablecolumns_tags' 'cf_fluidcontent' 'cf_fluidcontent_tags'
'cf_flux' 'cf_flux_tags' 'cf_vhs_main' 'cf_vhs_main_tags' 'cf_vhs_markdown' 'cf_vhs_markdown_tags' 'link_cache' 'link_oldlinks' 'tt_news_cache' 'sys_history' 'sys_log' 'sys_dmail_maillog')
# Ignore cache tables from the list
for TABLE in ${EXCLUDES[*]}; do
  IGNORES+=" --ignore-table=$1.$TABLE"
done
# Export db struct
echo "Executing: mysqldump --routines $1 -d > $2"
mysqldump --routines $1 -d > $2
# Export data without ignored tables
echo "Executing: mysqldump $1 -nt $IGNORES >> $2"
mysqldump $1 -nt $IGNORES >> $2