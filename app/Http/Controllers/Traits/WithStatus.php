<?php

namespace App\Http\Controllers\Traits;

trait WithStatus
{
    /**
     * Return message
     *
     * @param  string $status
     * @param  string $message
     * @return array
     */
    protected function message($status, $message)
    {
        return [
            'type'    => $status,
            'message' => $message,
        ];
    }

    /**
     * Return success message
     *
     * @param  string $message
     * @return array
     */
    protected function success($message)
    {
        return $this->message('success', $message);
    }

    /**
     * Return error message
     *
     * @param  string $message
     * @return array
     */
    protected function error($message)
    {
        return $this->message('danger', $message);
    }

    /**
     * Return create success message
     *
     * @param  string $message
     * @return array
     */
    protected function createSuccess($message = '新增成功')
    {
        return $this->success($message);
    }

    /**
     * Return update success message
     *
     * @param  string $message
     * @return array
     */
    protected function updateSuccess($message = '修改成功')
    {
        return $this->success($message);
    }

    /**
     * Return delete success message
     *
     * @param  string $message
     * @return array
     */
    protected function deleteSuccess($message = '刪除成功')
    {
        return $this->success($message);
    }
}
