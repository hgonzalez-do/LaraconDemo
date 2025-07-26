<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DatabaseController extends Controller
{
    /**
     * Display a listing of all database tables.
     */
    public function tables()
    {
        try {
            // Query to get all tables from PostgreSQL
            $tables = collect(DB::select("
                SELECT tablename, schemaname 
                FROM pg_tables 
                WHERE schemaname NOT IN ('information_schema', 'pg_catalog')
                ORDER BY tablename
            "));

            // Alternative query using information_schema (more standard SQL)
            // $tables = collect(DB::select("
            //     SELECT table_name, table_schema
            //     FROM information_schema.tables 
            //     WHERE table_type = 'BASE TABLE' 
            //     AND table_schema NOT IN ('information_schema', 'pg_catalog')
            //     ORDER BY table_name
            // "));

            return view('database.tables', compact('tables'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to fetch database tables: ' . $e->getMessage()]);
        }
    }

    /**
     * Show table structure/schema.
     */
    public function showTable($tableName)
    {
        try {
            // Get table columns information
            $columns = DB::select("
                SELECT 
                    column_name,
                    data_type,
                    is_nullable,
                    column_default,
                    character_maximum_length,
                    numeric_precision,
                    numeric_scale
                FROM information_schema.columns 
                WHERE table_name = ? 
                AND table_schema = 'public'
                ORDER BY ordinal_position
            ", [$tableName]);

            // Get table indexes
            $indexes = DB::select("
                SELECT 
                    indexname,
                    indexdef
                FROM pg_indexes 
                WHERE tablename = ? 
                AND schemaname = 'public'
            ", [$tableName]);

            // Get foreign keys
            $foreignKeys = DB::select("
                SELECT
                    kcu.column_name,
                    ccu.table_name AS foreign_table_name,
                    ccu.column_name AS foreign_column_name
                FROM information_schema.table_constraints AS tc
                JOIN information_schema.key_column_usage AS kcu
                    ON tc.constraint_name = kcu.constraint_name
                    AND tc.table_schema = kcu.table_schema
                JOIN information_schema.constraint_column_usage AS ccu
                    ON ccu.constraint_name = tc.constraint_name
                    AND ccu.table_schema = tc.table_schema
                WHERE tc.constraint_type = 'FOREIGN KEY'
                AND tc.table_name = ?
            ", [$tableName]);

            return view('database.table-structure', compact('tableName', 'columns', 'indexes', 'foreignKeys'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to fetch table structure: ' . $e->getMessage()]);
        }
    }

    /**
     * Browse table data with pagination.
     */
    public function browseTableData(Request $request, $tableName)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);
            $offset = ($page - 1) * $perPage;

            // Get total count
            $totalCount = DB::table($tableName)->count();

            // Get paginated data
            $data = DB::table($tableName)
                ->offset($offset)
                ->limit($perPage)
                ->get();

            // Get column names
            $columns = [];
            if ($data->isNotEmpty()) {
                $columns = array_keys((array) $data->first());
            }

            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'last_page' => ceil($totalCount / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalCount)
            ];

            return view('database.table-data', compact('tableName', 'data', 'columns', 'pagination'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to fetch table data: ' . $e->getMessage()]);
        }
    }

    /**
     * Get database statistics.
     */
    public function stats()
    {
        try {
            // Get database size
            $dbSize = DB::select("
                SELECT pg_size_pretty(pg_database_size(?)) as size
            ", [config('database.connections.pgsql.database')]);

            // Get table sizes
            $tableSizes = DB::select("
                SELECT 
                    tablename,
                    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) as size,
                    pg_total_relation_size(schemaname||'.'||tablename) as size_bytes
                FROM pg_tables 
                WHERE schemaname = 'public'
                ORDER BY size_bytes DESC
            ");

            return view('database.stats', compact('dbSize', 'tableSizes'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Unable to fetch database statistics: ' . $e->getMessage()]);
        }
    }
}