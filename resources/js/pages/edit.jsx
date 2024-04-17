import React, { useState } from "react";
import { ChevronLeft } from "lucide-react";
import Layout from "@/components/elements/layout";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Link } from "@inertiajs/inertia-react";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { EditorState, convertToRaw } from "draft-js";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import draftToHtml from "draftjs-to-html";

const formSchema = z.object({
    title: z.string().min(1, "Title harus di isi."),
    content: z.string().min(1, "Content harus di isi."),
});


export default function CreateForum() {
    const token = localStorage.getItem("token");
    const [editorState, setEditorState] = useState(EditorState.createEmpty());
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
        reset,
        setValue,
    } = useForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            title: "",
            content: "",
        },
    });
    const onEditorStateChange = function (editorState) {
        setEditorState(editorState);
        setValue(
            "content",
            draftToHtml(convertToRaw(editorState.getCurrentContent()))
        );
    };

    const uploadCallback = (file) => {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const url = reader.result;
                resolve({ data: { link: url } });
            };
            reader.onerror = (error) => {
                reject(error);
            };
        });
    };

    const submit = async (data) => {
        const { title, content } = data;
        const fData = new FormData();
        fData.append("title", title);
        fData.append("content", content);

        await axios
            .post(
                "/api/v1/forum",
                {
                    title: title,
                    content: content,
                },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            )
            .then((res) => {
                toast.success(res.data.message);
                reset();
                setValue("content", "");
            })
            .catch((err) => {
                toast.error(err.response.data.message);
            });
    };
    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <Button variant="ghost" size="icon" asChild>
                    <Link href="/">
                        <ChevronLeft />
                    </Link>
                </Button>
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Ubah Forum
                </h3>
                <span></span>
            </div>
            <div className="w-full flex flex-col gap-y-4">
                <Card className="w-full bg-white p-4 rounded-lg shadow-md">
                    <CardContent>
                        <div className="flex items-center space-x-4 my-4">
                            <Avatar>
                                <AvatarImage alt="User profile picture" />
                                <AvatarFallback>EP</AvatarFallback>
                            </Avatar>
                            <div>
                                <div className="font-semibold text-lg">
                                    Eka Prasetyo S.Pd
                                </div>
                                <div className="text-gray-500">
                                    Kepala Sekolah SMK Bintara
                                </div>
                            </div>
                        </div>
                        <form
                            className="flex flex-col space-y-4"
                            onSubmit={handleSubmit}
                        >
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="title">Judul</Label>
                                <Input
                                    id="title"
                                    placeholder="Title"
                                    defaultValue="edit"
                                />
                            </div>
                            <div className="flex flex-col space-y-2">
                                <Label htmlFor="content">Konten</Label>
                                <ReactQuill
                                    theme="snow"
                                    value={value}
                                    onChange={handleInputChange}
                                />
                            </div>
                            <Button
                                className="bg-primary text-primary-foreground"
                                type="submit"
                            >
                                Tambah Forum
                            </Button>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </Layout>
    );
}
