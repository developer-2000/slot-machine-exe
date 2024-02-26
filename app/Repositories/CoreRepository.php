<?php
namespace App\Repositories;

abstract class CoreRepository {

    protected $model;

    // CREATE ===========================================
    // создать
    public function create(array $arr){
        return $this->model->create($arr);
    }

    // множественное создание с заполнением полей даты
    public function insertAddDate(array $arr){
        $object = $this->model;

        // заполнение полей create, update
        $insertData = array_map(function ($data) use ($object) {
            $timestamp = $object->freshTimestampString();
            $data[$object->getUpdatedAtColumn()] = $timestamp;
            $data[$object->getCreatedAtColumn()] = $timestamp;
            return $data;
        }, $arr);

        return $object->insert($insertData);
    }

    // UPDATE ===========================================
    // обновить по id
    public function update(int $id, array $arr){
        return $this->model->where('id', $id)->update($arr);
    }

    // обновить по where
    public function updateWhere(array $where, array $arr){
        return $this->model->where($where)->update($arr);
    }

    // обновить по where или создать
    public function updateOrCreate(array $where, array $arr){
        return $this->model->updateOrCreate($where, $arr);
    }

    // DELETE ===========================================

    // удалить по id
    public function delete(int $id){
        return $this->model->find($id)->delete();
    }

    // удалить по where all
    public function deleteWhereAll(array $arr){
        return $this->model->where($arr)
            ->delete();
    }
    // ===========================================
    // Select
    // ===========================================
    // выбрать все
    public function get(){
        return $this->model->get();
    }

    // ===========================================
    // выбрать по id
    public function show(int $id){
        return $this->model->find($id);
    }

    // ===========================================
    // выбрать Все по where
    public function whereAll(array $arr){
        return $this->model->where($arr)->get();
    }

    // ===========================================
    // выбрать Один по where
    public function whereArrFirst(array $arr){
        return $this->model->where($arr)
            ->latest('id')->first();
    }


    // ===========================================
    // выбрать Все по id и user_id
    public function owner($user_id, $id){
        return $this->model
            ->where('user_id', $user_id)
            ->where('id', $id)
            ->get();
    }

    // ===========================================
    // выбрать Все по масиву id
    public function whereIn(string $column, $arr_id){
        return $this->model->whereIn($column, $arr_id)->get();
    }

    // =========================================
    // выбрать количество всех записей
    public function getCountAll(){
        return $this->model
            ->count();
    }

}
